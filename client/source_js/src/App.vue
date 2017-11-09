<template>
  <div id="app" class="clearfix">
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'app'
}
</script>

<style>
.clearfix:before,
.clearfix:after {
  content: '';
  display: table;
}

.clearfix:after {
  clear: both;
}

body {
  width: 1100 px;
  margin: 0 auto;
  background: #e2e2e2;
  font-size: 14px;
}

#app {
  text-align: center;
  height: 100%;
}

.link {
  cursor: pointer;
}
</style>
