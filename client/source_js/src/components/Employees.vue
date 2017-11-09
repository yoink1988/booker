<template>
  <div class="employees">
    <div v-if="content == 'list'">
      <p>
        <router-link to='/'><button class="btn btn"> Back to Calendar</button></router-link>
      </p>
      <p>
        <button class="btn btn" @click="setContent('add')">Add new employee</button>
      </p>
    </div>
    <div v-else>
      <p>
        <button class="btn btn" @click="setContent('list')">Back to Emploees list</button>
      </p>
    </div>
    <div v-if="content == 'add'">
      <add-section :user="user"></add-section>
    </div>
    <div v-if="content == 'edit'">
      <edit-section :user="user" :employee="employeeToEdit"></edit-section>
    </div>
    <div v-if="content == 'list'">
      <div v-if="employees" class="row">
        <div class="col content">
          <table class="table">
            <thead>
            </thead>
            <tbody>
              <tr v-for="emp in employees">
                <td>
                  <a :href="'mailto:' + emp.email">{{emp.name}}</a>
                </td>
                <td>
                  <a class="link" @click="edit(emp)">Edit</a>
                </td>
                <td v-if="user.id != emp.id">
                  <a class="link" @click="showRemove(emp)">Remove</a>
                </td>
                <td v-else>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div v-if="!employees">
      </div>
      {{msg}}
    </div>
    <div v-if="content == 'remove'">
      <remove-section :emp="empToRemove" :user="user"></remove-section>
    </div>
  </div>
</template>

<script>
import AddEmployee from './AddEmployee.vue'
import EditEmployee from './EditEmployee.vue'
import RemoveEmployee from './RemoveEmployee.vue'
export default {
  name: 'Employees',
  data() {
    return {
      employees: [],
      user: {},
      msg: '',
      content: 'list',
      employeeToEdit: {},
      empToRemove: {}
    }
  },
  created() {
    this.getStorageData()
    this.getEmployees()
  },
  components: {
    'add-section': AddEmployee,
    'edit-section': EditEmployee,
    'remove-section': RemoveEmployee
  },
  methods: {
    getStorageData: function() {
      var self = this
      if (localStorage['user']) {
        self.user = JSON.parse(localStorage['user'])
        self.checkAuth(self.user.id, self.user.hash)
      } else {
        self.$router.push('/')
      }
    },
    setContent: function(content) {
      var self = this
      self.content = content
    },
    checkAuth: function(id, hash) {
      var self = this
      var xhr = new XMLHttpRequest()
      xhr.open("GET", getUrl() + 'auth/', true)
      xhr.setRequestHeader("Authorization", "Basic " + btoa(id + ":" + hash));
      xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return
        if (xhr.status != 200) {
        } else {
          var res = JSON.parse(xhr.responseText)
          if (!res) {
            self.$router.push('/')
          }
        }
      }
      xhr.send()
    },
    getEmployees: function() {
      var self = this
      var xhr = new XMLHttpRequest()
      xhr.open('GET', getUrl() + 'users/', true)
      xhr.setRequestHeader("Authorization", "Basic " + btoa(self.user.id + ":" + self.user.hash));
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return
        if (xhr.status != 200) {
          alert(xhr.status + ': ' + xhr.statusText)
        } else {
          var res = JSON.parse(xhr.responseText)
          if (res) {
            self.employees = res
          } else {
            self.employees = []
            self.msg = 'No users Found'
          }
        }
      }
      xhr.send();
    },
    edit: function(employee) {
      var self = this
      self.employeeToEdit = employee
      self.content = 'edit'

    },
    showRemove: function(emp) {
      var self = this
      self.empToRemove = emp
      self.setContent('remove')
    }

  },

}
</script>

<style scoped>
.employees p {
  margin-top: 20px;
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

.row {
  margin: auto;
}

.content {
  width: 800px;
  margin: 30px auto;
}
</style>
