<template>
  <div v-if="events.length">
    <player :events="events" ref="player" @loadMore="loadMore" :custom-meta="meta"></player>
  </div>
</template>

<script>

import Player from "./components/Player";
import axios from 'axios';

export default {
  components: {
    Player
  },
  props: {
    recordId: ""
  },
  mounted() {
    // this.loadEvents();

    this.init();
  },
  data: () => ({
    meta: {
      startTime: 0,
      endTime: 0,
      totalTime: 0,
    },
    lastPage: false,
    events: [],
    page: 0,
    updateInterval: 0,
  }),
  methods: {
    requestEvents() {
      let url = '/paginated/' + window.recordId + '?page=' + this.page;

      return this.request(url)
    },
    async init() {
      let url = '/initial/' + window.recordId

      const {events, meta} = await this.request(url)

      this.events = events;
      this.meta = meta;
    },
    request(url) {
      const service = axios.create({
        baseURL: '/api/record/data',
        timeout: 20000, // Request timeout
      })
      service.interceptors.response.use(
        response => {

          return response.data;
        },
        error => {
          return Promise.reject(error);
        }
      );

      return service({
        url: url,
        method: 'get',
      })
    },
    loadMore() {
      if (!this.lastPage) {
        if(this.page > 0){
          // return
        }
        this.$refs.player.replayer.pause()
        this.loadEvents();
      }
    },
    async loadEvents() {
      const {events} = await this.requestEvents();
      if (events.length === 0) {
        console.log('last page')
        this.lastPage = true;
        return
      }

      events.forEach(event => {
        this.$refs.player.replayer.addEvent(event)
      })

      this.$refs.player.replayer.resetCache();

      const startTime = events[0].timestamp;

      const playStartFrom = startTime - this.meta.startTime;

      console.log(this.page, playStartFrom);

      this.page = this.page + 1;

      console.log("play click")

      setTimeout(() => {
        this.$refs.player.replayer.play(playStartFrom)
        console.log("play click end")
      }, 0)
    }
  }
}
</script>
