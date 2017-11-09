<template>
  <div class="remove-employee">
    <p> Are you sure you want to delete contact
      <span><b>{{emp.name}}</b>?</span>
    </p>
    <button class="btn btn-default" @click="remove()">YES</button>
    <button @click="$parent.content = 'list'" class="btn btn-default">Back</button>
    <p>{{msg}}</p>
  </div>
</template>

<script>
export default {
  props: ['emp', 'user'],
  name: 'HelloWorld',
  data() {
    return {
      msg: ''
    }
  },
  methods: {
    remove: function() {
      var self = this
      self.msg = ''
      var xhr = new XMLHttpRequest();
      xhr.open("DELETE", getUrl() + 'users/id/' + self.emp.id, true);
      xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
      xhr.setRequestHeader("Authorization", "Basic " + btoa(self.user.id + ":" + self.user.hash));
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return
        if (xhr.status != 200) {
          alert(xhr.status + ': ' + xhr.statusText)
        } else {
          var res = JSON.parse(xhr.responseText)
          if (res) {
            self.msg = 'Deleted'
            self.$parent.getEmployees()
          }
          else {
            self.msg = 'Fail'
          }
        }
      }
      xhr.send(null);
    },
  },
}
</script>

<style scoped>
h1,
h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
</style>
