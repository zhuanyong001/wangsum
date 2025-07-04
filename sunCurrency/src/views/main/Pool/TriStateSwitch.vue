<template>
    <div class="switch-container">
      <div class="switch">
        <div 
          v-for="(state, index) in states"
          :key="state.key"
          class="switch-tab"
          :class="{ active: currentState === state.key }"
          @click="setState(state.key)"
        >
          {{ state.title }}
        </div>
        <div class="switch-handle" :class="'state-' + currentState"></div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      value: {
        type: String,
        default: '0'
      },
      states: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        currentState: this.value
      }
    },
    watch: {
      value(newVal) {
        this.currentState = newVal;
      }
    },
    methods: {
      setState(state) {
        if (this.currentState !== state) {
          this.currentState = state;
          this.$emit('change', state);
        }
      }
    }
  }
  </script>
  
  <style lang="scss" scoped>
  $switch-width: 16.67rem;
  $switch-height: 2.5rem;
  $switch-handle-height: 2.1rem;
  $switch-margin: 1.67rem;
  $switch-handle-top: 0.08rem;
  $switch-handle-left: 0.08rem;
  
  .switch-container {
    display: flex;
    align-items: center;
    position: relative;
    width: $switch-width;
    margin: $switch-margin;
    margin-left:0;
    margin-right:0;
    
    .switch {
      display: flex;
      align-items: center;
      position: relative;
      width: 100%;
      height: $switch-height;
      background-color: var(--bgColor3);
      border-radius: $switch-height;
      border: 1px solid var(--bgColor2);
      overflow: hidden;
      cursor: pointer;
      background: linear-gradient( 225deg, #242928 0%, #0D1114 100%);
      .switch-tab {
        flex: 1;
        text-align: center;
        line-height: $switch-height;
        z-index: 1;
        color: #fff;
        transition: color 0.3s;
        font-size: 1rem;
        &.active {
          color: var(--textColor4);
          font-weight: 600;
        }
      }
      .switch-handle {
        position: absolute;
        top: $switch-handle-top;
        width: 33%;
        height: $switch-handle-height;
        @include colour-background();
        border-radius: $switch-height;
        transition: left 0.3s;
        &.state-0 {
          left: $switch-handle-left;
        }
        &.state-1 {
          left: 34%;
        }
        &.state-2 {
          left: 67%;
        }
      }
    }
  }
  </style>
  