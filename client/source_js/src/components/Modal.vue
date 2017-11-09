<template>
  <div class="modal">
    <transition name="modal">
      <div class="modal-mask ">
        <div class="modal-wrapper ">
          <div class="modal-container text-center center-block">
            <slot name="header">
              <span style="font-weight:bold; font-size:15px;">Event Details </span>
            </slot>
            <slot name="body">
              <div v-if="( (currDate < eventStartPoint) && (isOwner || isAdmin) && (!isDeleted)) "> Time:
                <select v-model="startH">
                  <option v-for="h in hoursSelector" :value="h.value">{{h.title}}</option>
                </select>
                <select v-model="startM">
                  <option v-for="m in minutesSelector" :value="m.value">{{m.title}}</option>
                </select>
                <select v-if="timeFormat == '12'" v-model="modeStart">
                  <option :value="mode[0]">{{mode[0]}}</option>
                  <option :value="mode[1]">{{mode[1]}}</option>
                </select>
                -
                <select v-model="endH">
                  <option v-for="h in hoursSelector" :value="h.value">{{h.title}}</option>
                </select>
                <select v-model="endM">
                  <option v-for="m in minutesSelector" :value="m.value">{{m.title}}</option>
                </select>
                <select v-if="timeFormat == '12'" v-model="modeEnd">
                  <option :value="mode[0]">{{mode[0]}}</option>
                  <option :value="mode[1]">{{mode[1]}}</option>
                </select>
              </div>
              <div v-if="((currDate > eventStartPoint)) || (!isOwner && !isAdmin) || isDeleted">
                Time:
                <span>{{startH}}:{{endM}} - {{endH}}:{{endM}}</span>
              </div>
              <div style="margin-top:10px;margin-bottom:10px;" v-if="( (currDate < eventStartPoint) && (isOwner || isAdmin) && (!isDeleted))">
                Notes:
                <textarea v-model="event.descr"></textarea>
              </div>
              <div v-if="((!isOwner && !isAdmin) || (currDate > eventStartPoint) || isDeleted)">
                Notes:
                <span>{{event.descr}}</span>
              </div>
              <div v-if="isAdmin && (currDate < eventStartPoint) && (!isDeleted)">
                Who:
                <select v-if="employees" v-model="selectedUser">
                  <option v-for="emp in employees" :value="emp.id">{{emp.name}}</option>
                </select>
              </div>
              <div v-if="!isAdmin || (currDate > eventStartPoint) || (isDeleted)">
                Who:
                <span>{{event.u_name}}</span>
              </div>
              <p></p>
              <p> PubDate: {{event.submit}}</p>
              <div v-if="( (currDate < eventStartPoint) && (isOwner || isAdmin) && (!isDeleted))">
                <div v-if="isRecc">
                  <input v-model="aplyToRec" type="checkbox">Apply to occurencies, (starts from this event)
                </div>
                <div class="butt-act">
                  <button class="btn btn" @click="update()">update</button>
                  <button class="btn btn" @click="remove()">delete</button>
                </div>
              </div>
              <button class=" btn btn col-xs-offset-10" @click="$emit('close')"> OK </button>
              <div v-if="msg">
                <p v-if="!Array.isArray(msg)">{{msg}}</p>
                <p v-if="typeof(msg) == 'object'" v-for="mess in msg">{{mess}}</p>
              </div>
            </slot>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ['event', 'user', 'timeFormat'],
  name: 'Modal',
  data() {
    return {
      modeStart: '',
      modeEnd: '',
      mode: ['AM', 'PM'],
      isRecc: false,
      aplyToRec: false,
      eventStartPoint: '',
      startH: '',
      startM: '',
      endH: '',
      endM: '',
      users: '',
      minutes: ['00', '30'],
      selectedUser: '',
      employees: {},
      isOwner: false,
      isAdmin: false,
      msg: '',
      isDeleted: false
    }
  },
  created() {
    this.isUserAdmin()
    this.isUserOwner()
    this.getParametres()
    this.isReccuring()
    this.getUsers()
  },
  methods: {
    update: function() {
      var self = this
      var data = {}
      var timeStart = new Date(self.event.start)
      var timeEnd = new Date(self.event.end)
      if (self.timeFormat == '24') {
        timeStart.setHours(self.startH, self.startM)
        timeEnd.setHours(self.endH, self.endM)
      }
      if (self.timeFormat == '12') {
        if (self.modeStart == 'AM') {
          timeStart.setHours(self.startH, self.startM)
        } else {
          if (self.startH == 12) {
            timeStart.setHours(self.endH, self.startM)
          } else {
            timeStart.setHours(+self.startH + 12, self.startM)
          }
        }
        if (self.modeEnd == 'AM') {

          timeEnd.setHours(self.endH, self.endM)
        }
        if (self.modeEnd == 'PM') {
          if (self.endH == 12) {
            timeEnd.setHours(self.endH, self.endM)
          } else {
            timeEnd.setHours(self.endH + 12, self.endM)
          }
        }
      }

      data.id = self.event.id
      data.id_room = self.event.room_id
      var details = {}
      details.startPoint = self.eventStartPoint.getTime()
      details.id_user = self.selectedUser
      details.timeStart = timeStart.getTime()
      details.timeEnd = timeEnd.getTime()
      details.desc = self.event.descr
      data.details = details
      if (self.isRecc && self.aplyToRec) {
        data.occur = true
      }
      var xhr = new XMLHttpRequest();
      var json = JSON.stringify(data);
      xhr.open("PUT", getUrl() + 'events/', true)
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
            self.msg = 'Event updated'
          }
          else {
            self.msg = res
          }
          self.$parent.getEvents()
        }
      }
      xhr.send(json)
    },
    remove: function() {
      var self = this
      self.msg = ''
      var xhr = new XMLHttpRequest();
      var url = getUrl() + 'events/id/' + self.event.id + '/from/' + self.eventStartPoint.getTime() + '/user/' + self.user.id
      if (self.aplyToRec) {
        url += '/all/true'
      }
      xhr.open("DELETE", url, true);
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
          }
        }
        self.$parent.getEvents()
        self.isDeleted = true
      }
      xhr.send(null);
    },
    isUserOwner: function() {
      if (this.user.id == this.event.u_id) {
        this.isOwner = true
      }
    },
    isUserAdmin: function() {
      if (this.user.id_role == ADMIN) {
        this.isAdmin = true
      }
    },
    getUsers: function() {
      var self = this
      var xhr = new XMLHttpRequest()
      if (self.user.id_role == ADMIN) {
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
              self.isDeleted = true
            }
          }
        }
        xhr.send();
      } else {
        self.employees = [{ id: self.event.u_id, name: self.event.u_name }]
      }
    },
    getParametres: function() {
      var self = this
      self.currDate = new Date()
      self.selectedUser = self.event.u_id
      var tmpS = new Date(self.event.start)
      var tmpE = new Date(self.event.end)
      self.eventStartPoint = tmpS

      if (self.timeFormat == '24') {
        self.startH = tmpS.getHours()
        self.endH = tmpE.getHours()
      } else {
        if (tmpS.getHours() >= 12) {
          self.modeStart = 'PM'
          if (tmpS.getHours() == 12) { self.startH = tmpS.getHours() }
          else {
            self.startH = tmpS.getHours() - 12
          }
        } else {
          self.modeStart = 'AM'
          self.startH = tmpS.getHours()
        }
        if (tmpE.getHours() >= 12) {
          self.modeEnd = 'PM'
          if (tmpE.getHours() == 12) { self.endH = tmpE.getHours() }
          else {
            self.endH = tmpE.getHours() - 12
          }
        } else {
          self.modeEnd = 'AM'
          self.endH = tmpE.getHours()
        }

      }
      if (tmpS.getMinutes() == '0') {
        self.startM = '00'
      } else {
        self.startM = tmpS.getMinutes()
      }
      if (tmpE.getMinutes() == '0') {
        self.endM = '00'
      } else {
        self.endM = tmpE.getMinutes()
      }
    },
    isReccuring: function() {
      var self = this
      if (!self.isAdmin && !self.isOwner) {
        return
      }
      var xhr = new XMLHttpRequest()
      xhr.open('GET', getUrl() + 'events/id/' + self.event.id + '/count/true/user/' + self.user.id, true)
      xhr.setRequestHeader("Authorization", "Basic " + btoa(self.user.id + ":" + self.user.hash));
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return
        if (xhr.status != 200) {
          alert(xhr.status + ': ' + xhr.statusText)
        } else {
          var res = JSON.parse(xhr.responseText)
          if (res > 1) {
            self.isRecc = true
          }
        }
      }
      xhr.send();
    }
  },
  computed: {
    hoursSelector() {
      var self = this
      var hours = []
      if (self.timeFormat == '12') {
        for (var i = 1; i <= 12; i++) {
          hours.push({ value: i, title: i })
        }
      } else {
        for (var i = 8; i <= 20; i++) {
          hours.push({ value: i, title: i })
        }
      }
      return hours
    },
    minutesSelector() {
      var self = this
      var minutes = []
      self.minutes.forEach(function(m) {
        minutes.push({ value: m, title: m })
      })
      return minutes
    }
  }
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

.modal {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.butt-act button {
  margin-left: 10px;
  margin-right: 10px;
}

.modal-container {
  width: 500px;
   margin: 10% 33%; 
  display:block;
  padding: 20px 30px;
  background-color: #e2e2e2;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
