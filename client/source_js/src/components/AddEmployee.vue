<template>
  <div class="reg row">
    <div id="regform" class="col-md-5" style="width:400px">
      <form role="form ">
        <div class="form-group">
          <label for="exampleInputEmail1">Email</label>
          <input v-model="email" type="email" class="form-control" id="exampleInputEmail" placeholder="Enter email">
          <small class="text-muted">
            example@gmail.com
          </small>
        </div>
        <div class="form-group">
          <label for="exampleInputName">Name</label>
          <input v-model="name" type="text" class="form-control" id="exampleInputPassword1" placeholder="Name">
          <small id="passwordHelpInlinee" class="text-muted">
            Must be 3-20 characters long, contain letters, and must not contain spaces, special characters, or emoji.
          </small>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input v-model="pass" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          <small id="passwordHelpInlinee" class="text-muted">
            Must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
          </small>
        </div>
        <button @click="addUser()" class="btn btn-default">Submit</button>
        <button @click="$parent.content = 'list'" class="btn btn-default">Back</button>
        <p style="padding:10px">{{msg}}</p>
      </form>
    </div>

  </div>
</template>

<script>
export default {
  name: 'Register',
  props: ['user'],
  data() {
    return {
      msg: '',
      email: '',
      name: '',
      pass: '',
    }
  },
  methods: {
    addUser: function() {
      var self = this
      if (self.name && self.email && self.pass) {
        var xhr = new XMLHttpRequest();
        var json = JSON.stringify({
          name: self.name,
          email: self.email,
          pass: self.pass
        });

        xhr.open("POST", getUrl() + 'users/', true)
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader("Authorization", "Basic " + btoa(self.user.id + ":" + self.user.hash));
        xhr.onreadystatechange = function() {
          if (xhr.readyState != 4) return
          if (xhr.status != 200) {
            alert(xhr.status + ': ' + xhr.statusText)
          } else {
            var res = JSON.parse(xhr.responseText)
            if (res === true) {
              self.msg = 'User Added'
              self.email = ''
              self.name = ''
              self.pass = ''
              self.$parent.getEmployees()
            }
            else
              self.msg = res
          }
        }
        xhr.send(json)
      } else {
        self.msg = 'All fields are required'
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style >
.reg {
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
