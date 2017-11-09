<template>
  <div class="edit-employee row">
    <div id="regform" class="col-md-5" style="width:400px">
      <form role="form ">
        <div class="form-group">
          <label for="exampleInputEmail1">New Email</label>
          <input v-model="employee.email" type="email" class="form-control" id="exampleInputEmail" placeholder="Enter email">
          <small class="text-muted">
            example@gmail.com
          </small>
        </div>
        <div class="form-group">
          <label for="exampleInputName">New Name</label>
          <input v-model="employee.name" type="text" class="form-control" id="exampleInputPassword1" placeholder="Name">
          <small id="passwordHelpInlinee" class="text-muted">
            Must be 3-20 characters long, contain letters, and must not contain spaces, special characters, or emoji.
          </small>
        </div>
        <button @click="save()" class="btn btn-default">Save</button>
        <button @click="$parent.content = 'list'" class="btn btn-default">Back</button>
        <p style="padding:10px">{{msg}}</p>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EditEmployee',
  props: ['user', 'employee'],
  data() {
    return {
      msg: '',
    }
  },
  methods: {
    save: function() {
      var self = this
      var xhr = new XMLHttpRequest();
      var json = JSON.stringify(self.employee);
      xhr.open("PUT", getUrl() + 'users/', true)
      xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
      xhr.setRequestHeader("Authorization", "Basic " + btoa(self.user.id + ":" + self.user.hash));
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return
        if (xhr.status != 200) {
          alert(xhr.status + ': ' + xhr.statusText)
        }
        else {
          var res = JSON.parse(xhr.responseText)
          if (res === true) {
            self.msg = 'Updated'
          }
          else {
            self.msg = res
          }
          self.$parent.getEmployees()
        }
      }
      xhr.send(json)
    },
  }



}
</script>

<style >
.edit-employee {
  width: 400px;
  margin: 30px auto;
}

#regform {
  padding: 30px;
}

a {
  color: #42b983;
}
</style>
