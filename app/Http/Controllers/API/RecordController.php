<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RecordData;
use App\Repositories\RecordDataRepository;
use App\Repositories\RecordRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RecordController extends Controller
{
    private $recordRepository;
    private $recordDataRepository;

    /**
     * @param RecordRepository $recordRepository
     * @param RecordDataRepository $recordDataRepository
     */
    public function __construct(RecordRepository $recordRepository, RecordDataRepository $recordDataRepository)
    {
        $this->recordRepository = $recordRepository;
        $this->recordDataRepository = $recordDataRepository;
    }

    public function initRecord(): JsonResponse
    {
        $record = $this->recordRepository->create();

        return new JsonResponse([
            'id' => $record->record_id
        ]);
    }

    public function getInitialData($id): JsonResponse
    {
        $rows = RecordData::query()
            ->where('record_id', $id)
            ->orderBy('timestamp')
            ->limit(2)
            ->get();

        $events = $this->mapData($rows);
        $startTime = RecordData::query()
            ->where('record_id', $id)
            ->orderBy('timestamp')
            ->limit(1)
            ->value('timestamp');

        $endTime = RecordData::query()
            ->where('record_id', $id)
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->value('timestamp');

        $totalTime = $endTime - $startTime;

        return new JsonResponse([
            'events' => $events,
            'meta' => [
                'totalTime' => $totalTime,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ],
        ]);
    }

    public function mapData($rows): array
    {
        $result = [];
        /** @var RecordData $recordData */
        foreach ($rows as $recordData) {
            $type = $recordData->type;
            $data = $recordData->data;

            if ($type === 2) {
                $data = $this->mapEventData($data);
            }

            $result[] = [
                'id' => $recordData->id,
                'data' => $data,
                'type' => $type,
                'timestamp' => $recordData->timestamp,
            ];
        }

        return $result;
    }

    public function mapEventData(array $data): array
    {
        $node = Arr::get($data, 'node');

        if ($node && Arr::get($node, 'type') === 0) {
            $node['childNodes'] = $this->formatChildNodes(Arr::get($node, 'childNodes'));
        }

        $data['node'] = $node;

        return $data;
    }

    public function getRecordData($id): JsonResponse
    {
        $rows = RecordData::query()->where('record_id', $id)->limit(100)->orderBy("timestamp")->get();

        $result = $this->mapData($rows);

        return new JsonResponse($result);
    }

    public function getPaginatedRecordData($id): JsonResponse
    {
        $page = request('page', 0);

        $pageSize = 50;
        $offset = ($pageSize * $page) + 2;

        $rowQuery = RecordData::query()
            ->where('record_id', $id)
            ->orderBy('timestamp')
            ->limit($pageSize)
            ->offset($offset);

//        if ($page) {
//            $rowQuery->where("type", '!=', 4);
//        }

        $rows = $rowQuery->get();

        $result = $this->mapData($rows);

        return new JsonResponse(['events' => $result]);
    }

    public function formatChildNodes($childNodes): array
    {
        $excludeTags = ['meta', 'base'];

        $finalNodes = [];

        foreach ($childNodes as $node) {
            $nodeData = [];
            $tagName = Arr::get($node, 'tagName');
            $nodeType = Arr::get($node, 'type');

            if ($tagName && in_array(Arr::get($node, 'tagName'), $excludeTags)) {
                continue;
            }

            if ($nodeType == 5) {
                continue;
            }

            if ($nodeType == 3 && !Arr::get($node, 'textContent')) {
                continue;
            }

            if ($tagName === 'script') {
                $attributes = Arr::get($node, 'attributes');

                if (!$attributes || empty($attributes['src']) || !Str::endsWith($attributes['src'], '.js')) {
                    continue;
                }
            }

            foreach ($node as $key => $value) {
                if ($key == 'childNodes' || $value) {
                    $nodeData[$key] = $value;
                }
            }

            if ($childNodes = Arr::get($nodeData, 'childNodes')) {
                $nodeData['childNodes'] = $this->formatChildNodes($childNodes);
            }

            $finalNodes[] = $nodeData;
        }

        return $finalNodes;
    }

    public function saveRecord(Request $request): JsonResponse
    {
        $recordId = $request->get('recordId');
        $data = $request->get('data');

        foreach ($data as $recordData) {
            $data = $recordData['data'];
            $type = $recordData['type'];

            if ($type === 2) {
                $data = $this->mapEventData($data);
            }

            $this->recordDataRepository->save(
                $recordId,
                $data,
                $type,
                $recordData['timestamp']
            );
        }

        return new JsonResponse(['success' => true]);
    }
}
