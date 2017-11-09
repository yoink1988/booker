<template>
  <div class="container-fluide">
    <div class="row home clearfix">
      <button v-if="user.id" @click="logOut()" class="btn btn col-md-1 switch">Log Out</button>
      <div v-if="content == ''">
        <login-section></login-section>
      </div>
      <div @reload="getEvents" v-if="content == 'calendar'" class="col-md-12">
        <div v-if="user.id_role == 2">
          <router-link to="/employees"><button class="btn ">Employee List</button></router-link>
        </div>
        <div style="margin-top:30px;" class="row">
          <ul>
            <li v-for="room in rooms">
              <a style="font-size:110%" class="link" @click="setActiveRoom(room.id)">{{room.name}}</a>
            </li>
          </ul>
        </div>
        <div style="margin-top:20px;" v-if="activeRoomId" class="row">
          <span class="active-room">{{activeRoomName}}</span>
          <p>
            <a style="font-size:120%" class="link" @click="bookIt()">Book It!</a>
          </p>
        </div>
        <div class="calendar col cal-block">
          <a class="link" style="float:right;margin:15px;" @click="setWeekFirstDay()">Calendar type</a>
          <a class="link" style="float:right;margin:15px;" @click="setTimeFormat()">Change Time Format</a>
          <table class="table table-bordered table-class">
            <thead>
              <tr>
                <td colspan="2" @click="monthMinus()">&#9668</td>
                <td colspan="3">{{months_en[currMonth-1]}} {{currYear}}</td>
                <td colspan="2" @click="monthPlus()">&#9658</td>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-if="firstDayMonday" class="header-table">
                <td>Monday</td>
                <td>Tuesday</td>
                <td>Wednesday</td>
                <td>Thursday</td>
                <td>Friday</td>
                <td>Saturday</td>
                <td>Sunday</td>
              </tr>
              <tr v-if="!firstDayMonday" class="header-table">
                <td>Sunday</td>
                <td>Monday</td>
                <td>Tuesday</td>
                <td>Wednesday</td>
                <td>Thursday</td>
                <td>Friday</td>
                <td>Saturday</td>
              </tr>
              <tr v-for="row in calendarToDraw">
                <td v-for="day in row">
                  <span v-if="day.length > 1">{{day[0]}}</span>
                  <p v-for="event in day[1]">
                    <a @click="editEvent(event)" class="link"><b>{{event.timeString}}</b></a>
                  </p>
                  <span v-if="day.length == 1">{{day[0]}}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div v-if="content == 'bookit'">
        <bookit-section :user="user" :idRoom="activeRoomId" :timeFormat="timeFormat"></bookit-section>
      </div>
      <div v-if="activeEvent">
        <modal @close="activeEvent = false" :event="activeEvent" :user="user" :timeFormat="timeFormat"></modal>
      </div>
    </div>
  </div>
</template>

<script>
import Login from "./Login.vue";
import BookIt from "./BookIt.vue";
import Modal from "./Modal.vue";
export default {
  name: "Main",
  data() {
    return {
      months_en: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ],
      refreshed: false,
      currMonth: "",
      currYear: "",
      firstDayMonday: true,
      myCalendar: "",
      items: [],
      translateArray: {},
      rooms: [],
      events: [],
      user: {},
      activeRoomId: "",
      content: "calendar",
      isAdmin: false,
      timeFormat: "24",
      showModal: false,
      activeEvent: null,
      activeRoomName: ''
    };
  },
  components: {
    "login-section": Login,
    "bookit-section": BookIt,
    modal: Modal
  },
  methods: {
    setTimeFormat: function() {
      var self = this;
      if (self.timeFormat == "12") {
        self.timeFormat = "24";
        self.refreshed = false;
        return;
      } else {
        self.timeFormat = "12";
        self.refreshed = false;
        return;
      }
    },
    logOut: function() {
      var self = this;
      self.content = "";
      self.user = {};
      delete localStorage["user"];
    },
    editEvent: function(event) {
      var self = this;
      self.activeEvent = event;
    },
    bookIt: function() {
      var self = this;
      self.content = "bookit";
    },
    getStorageData: function() {
      var self = this;
      if (localStorage["user"]) {
        self.user = JSON.parse(localStorage["user"]);
        self.checkAuth(self.user.id, self.user.hash);
      } else {
        self.content = "";
      }
    },
    checkAuth: function(id, hash) {
      var self = this;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", getUrl() + "auth/", true);
      xhr.setRequestHeader("Authorization", "Basic " + btoa(id + ":" + hash));
      xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        if (xhr.status != 200) {
        } else {
          var res = JSON.parse(xhr.responseText);
          if (!res) {
            self.content = "";
          } else {
            self.getUserInfo();
          }
        }
      };
      xhr.send();
    },
    getUserInfo: function() {
      var self = this;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", getUrl() + "users/" + self.user.id, true);
      xhr.setRequestHeader(
        "Authorization",
        "Basic " + btoa(self.user.id + ":" + self.user.hash)
      );
      xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        if (xhr.status != 200) {
        } else {
          var res = JSON.parse(xhr.responseText);
          if (!res) {
            self.content = "";
          } else {
            self.user = res[0];
          }
          self.checkRole();
        }
      };
      xhr.send();
    },
    checkRole: function() {
      var self = this;
      if (self.user.id_role == ADMIN) {
        self.isAdmin = true;
      } else {
        self.isAdmin = false;
      }
    },

    createCalendar: function() {
      var self = this;
      var year = self.currYear;
      var month = self.currMonth;
      var mon = month - 1;
      var d = new Date(year, mon);
      self.items[0] = [];
      for (var i = 0; i < self.getDay(d); i++) {
        self.items[0].push({});
      }
      var row = 0;
      while (d.getMonth() == mon) {
        self.items[row].push([d.getDate()]);
        if (self.getDay(d) % 7 == 6) {
          row++;
          self.items[row] = [];
        }
        d.setDate(d.getDate() + 1);
      }
      self.refreshed = false;
    },
    getDay: function(date) {
      var self = this;
      var day = date.getDay();
      if (self.firstDayMonday) {
        if (day == 0) day = 7;
        return day - 1;
      } else {
        return day;
      }
    },
    getMonth: function() {
      var self = this;
      self.currMonth = new Date().getMonth() + 1;
    },
    getYear: function() {
      var self = this;
      self.currYear = new Date().getFullYear();
    },
    monthPlus: function() {
      var self = this;
      self.items = [];
      if (self.currMonth < 12) {
        self.currMonth++;
      } else {
        self.currYear++;
        self.currMonth = 1;
      }
      self.getEvents();
    },
    monthMinus: function() {
      var self = this;
      self.items = [];
      if (self.currMonth > 1) {
        self.currMonth--;
      } else {
        self.currYear--;
        self.currMonth = 12;
      }
      self.getEvents();
    },
    setWeekFirstDay: function() {
      var self = this;
      self.firstDayMonday = !self.firstDayMonday;
      self.refreshed = false;
      self.items = [];
      self.createCalendar();
    },
    getRooms: function() {
      var self = this;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", getUrl() + "rooms/", true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        if (xhr.status != 200) {
          alert(xhr.status + ": " + xhr.statusText);
        } else {
          var res = JSON.parse(xhr.responseText);
          if (res) {
            self.rooms = res;

            self.setActiveRoom(self.rooms[0].id);
                        // console.log(self.activeRoomId)
          } else {
            self.errMsg = "Rooms not found";
          }
        }
      };
      xhr.send();
    },
    getEvents: function() {
      var self = this;
      var xhr = new XMLHttpRequest();
      xhr.open(
        "GET",
        getUrl() +
        "events/year/" +
        self.currYear +
        "/month/" +
        self.currMonth +
        "/room/" +
        self.activeRoomId,
        true
      );
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        if (xhr.status != 200) {
          alert(xhr.status + ": " + xhr.statusText);
        } else {
          var res = JSON.parse(xhr.responseText);
          if (res) {
            self.events = res;
          } else {
            self.events = [];
          }
          self.createCalendar();
        }
      };
      xhr.send();
    },
    setActiveRoom: function(id) {
      var self = this;
      self.activeRoomId = id;
      self.rooms.forEach(function(el){
        if(el.id == id){
          self.activeRoomName = el.name
        }
      })
      self.getEvents();
    }
  },
  created() {
    this.getStorageData();
    this.getMonth();
    this.getYear();
    this.getRooms();
  },
  computed: {
    calendarToDraw() {
      var self = this;
      if (!self.refreshed) {
        var calendar = JSON.parse(JSON.stringify(self.items));
        calendar.forEach(function(week, key) {
          week.forEach(function(day) {
            if (day[0]) {
              var d = new Date(self.currYear, self.currMonth - 1, day[0]);
              self.events.forEach(function(ev) {
                var ed = new Date(ev.start);
                if (d.toDateString() == ed.toDateString()) {
                  var st_h = new Date(ev.start).getHours();
                  var st_m = new Date(ev.start).getMinutes();
                  var en_h = new Date(ev.end).getHours();
                  var en_m = new Date(ev.end).getMinutes();
                  if (st_m === 0) {
                    st_m = "00";
                  } else {
                    st_m = "30";
                  }
                  if (en_m === 0) {
                    var en_m = "00";
                  } else {
                    var en_m = "30";
                  }
                  if (self.timeFormat == "24") {
                    ev.timeString =
                      st_h + ":" + st_m + " - " + en_h + ":" + en_m;
                  }
                  if (self.timeFormat == "12") {
                    if (+st_h >= 12) {
                      if (+st_h == 12) {
                        var stStr = +st_h + ":" + st_m + " pm - ";
                      } else {
                        var stStr = +st_h - 12 + ":" + st_m + " pm - ";
                      }
                    } else {
                      var stStr = +st_h + ":" + st_m + " am - ";
                    }
                    if (+en_h >= 12) {
                      if (+en_h == 12) {
                        var enStr = +en_h + ":" + en_m + " pm";
                      } else {
                        var enStr = +en_h - 12 + ":" + en_m + " pm";
                      }
                    } else {
                      var enStr = +en_h + ":" + en_m + " am";
                    }
                    ev.timeString = stStr + enStr;
                  }
                  if (day.length == 1) {
                    day.push([ev]);
                  } else {
                    day[1].push(ev);
                  }
                }
                st_m = undefined;
              });
            }
          });
        });
        self.refreshed = true;
      }
      return calendar;
    }
  }
};
</script>

<style scoped>
.home {
  background: #e2e2e2;
}

.switch {
  float: right;
  margin-right: 30px;
  margin-top: 30px;
}

.header-table td {
  width: 350px;
}
.table-body td{
    height: 70px;
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

.calendar {
  margin: 0px auto;
}

.cal-block {
  width: 1200px;
}

.link {
  cursor: pointer;
}

.active-room {
  font-size: 18px;
  font-weight: bold;
}
</style>
