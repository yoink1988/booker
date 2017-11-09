<template>
  <div class="container-flide auth row">
    <div id="auth-form" class="col-md-offset-4 col-md-5" style="width:400px">
      <form role="form" class="container-for">
        <div class="form-group">
          <label for="exampleInputEmail1">Email</label>
          <input v-model="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input v-model="pass" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <button @click="login()" class="btn btn-default">Submit</button>
        <p style="padding:10px">{{msg}}</p>
      </form>
    </div>
  </div>
</template>

<script>
import Home from "./Home.vue";
export default {
  name: "Auth",
  data() {
    return {
      email: "",
      pass: "",
      msg: "",
      user: ""
    };
  },
  methods: {
    login: function() {
      var self = this;
      if (self.email && self.pass) {
        self.msg = "";
        var xhr = new XMLHttpRequest();
        var json = JSON.stringify({
          login: self.email,
          pass: self.pass
        });
        xhr.open("PUT", getUrl() + "auth/", true);
        xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
        xhr.onreadystatechange = function() {
          if (xhr.readyState != 4) return;
          if (xhr.status != 200) {
            alert(xhr.status + ": " + xhr.statusText);
          } else {
            var res = JSON.parse(xhr.responseText);
            if (!res) {
              self.msg = "Wrong login or password";
            } else {
              self.user = res[0];
              localStorage["user"] = JSON.stringify({
                id: self.user.id,
                hash: self.user.hash
              });
              self.$parent.content = "calendar";
              self.$parent.getStorageData();
            }
          }
        };
        xhr.send(json);
      } else {
        self.msg = "Wrong login or password";
      }
    }
  }
};
</script>

<style scoped>
.auth {
  background: #e2e2e2;
}

.container-for {
  background: #e2e2e2;
  padding: 30px;
}

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

.Auth {
  width: 400px;
  margin: 30px auto;
}

#auth-form {
  padding: 30px;
}
</style>
