<template>
  <div class="book-it">
    <div class="row">
      <div class="row form-login">
        <div class="col-xs-offset-3 col-sm-8 col-lg-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">
                <a class="link" @click="$parent.content = 'calendar'">Back to Calendar</a>
              </h3>
            </div>
            <div class="panel-body" style="background:#EEEEEE;">
              <div class="row">
                <div class="col-xs-offset-2 col-xs-8 col-sm-8 col-md-8 ">
                  <div class="input-group">
                    <span class="input-group-addon">Book for</span>
                    <select v-model="userToBook" class="form-control">
                      <option value="">select user</option>
                      <option v-for="emp in employees" :value="emp.id">{{emp.name}}</option>
                    </select>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Date</span>
                    <select v-model="selectedYear" class="col-md-4 form-control">
                      <option value="">Year</option>
                      <option v-for="year in yearSelector" :value="year.value">{{year.title}}</option>
                    </select>
                    <select v-model="selectedMonth" class="col-md-4 form-control">
                      <option value="">Month</option>
                      <option v-for="mon in monthSelector" :value="mon.value">{{mon.title}}</option>
                    </select>
                    <select v-model="selectedDay" class="col-md-4 form-control">
                      <option value="">Day</option>
                      <option v-for="day in daySelector" :value="day.value">{{day.title}}</option>
                    </select>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Time start</span>
                    <select v-model="timeStartH" class="col-md-4 time">
                      <option value="">HH</option>
                      <option v-for="h in hoursSelector" :value="h.value">{{h.title}}</option>
                    </select>
                    <select v-model="timeStartM" class="col-md-4 time">
                      <option v-for="m in minutesSelector" :value="m.value">{{m.title}}</option>
                    </select>
                    <div v-if="timeFormat == '12'">
                      <select v-model="modeStart" class="col-md-4 time">
                        <option :value="mode[0]">{{mode[0]}}</option>
                        <option :value="mode[1]">{{mode[1]}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon" style="padding-right:16px;">Time end</span>
                    <select v-model="timeEndH" class="col-md-4 time">
                      <option value="">HH</option>
                      <option v-for="s in hoursSelector" :value="s.value">{{s.title}}</option>
                    </select>
                    <select v-model="timeEndM" class="col-md-4 time">
                      <option v-for="m in minutesSelector" :value="m.value">{{m.title}}</option>
                    </select>
                    <div v-if="timeFormat == '12'">
                      <select v-model="modeEnd" class="col-md-4 time">
                        <option :value="mode[0]">{{mode[0]}}</option>
                        <option :value="mode[1]">{{mode[1]}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="input-group">
                    <span class="input-group-addon">Description</span>
                    <textarea class="form-control" v-model="descr" cols="30" rows="3"></textarea>
                  </div>
                  <div class="input-group col-md-offset-3">
                    <label> Is this going to be a recurring event?</label>
                    <div style="text-align:center;">
                      <input type="radio" id="one" :value="false" v-model="isReccuring">
                      <label for="one">No</label>
                    </div>
                    <div>
                      <input type="radio" id="two" :value="true" v-model="isReccuring">
                      <label for="two">Yes</label>
                    </div>
                  </div>
                  <div class="input-group col-md-offset-3">
                    <div v-if="isReccuring">
                      <label> recurring type</label>
                      <div v-for="t in recTypes">
                        <input :value="t" v-model="selectedRecurring" type="radio">
                        <label>{{t}}</label>
                      </div>
                      <div>
                        <input v-model="duration" type="text" size="4"> duration (max 4 weeks)
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-footer">
              <button @click="submit()" class="btn">Submit</button>
              <div v-if="msg" style="font-weight:bold; margin-top:5px;">
                <p v-if="!Array.isArray(msg)">{{msg}}</p>
                <p v-if="typeof(msg) == 'object'" v-for="mess in msg">{{mess}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "BookIt",
  props: ["user", "idRoom", "timeFormat"],
  data() {
    return {
      msg: "",
      duration: "",
      isReccuring: false,
      selectedRecurring: "weekly",
      recTypes: ["weekly", "be-weekly", "monthly"],
      descr: "",
      curtentDate: new Date(),
      employees: [],
      userToBook: "",
      selectedYear: "",
      yearsInDropdown: 2,
      mode: ["AM", "PM"],
      modeStart: "AM",
      modeEnd: "AM",
      minutes: ["00", "30"],
      selectedMonth: "",
      selectedDay: "",
      timeStartH: "",
      timeStartM: "00",
      timeEndH: "",
      timeEndM: "00",
      timeStartMod: "",
      timeEndMod: ""
    };
  },
  methods: {
    submit: function() {
      var self = this;
      self.msg = "";
      var ds = new Date(
        self.selectedYear,
        self.selectedMonth,
        self.selectedDay,
        8,
        0
      ).getTime();
      var de = new Date(
        self.selectedYear,
        self.selectedMonth,
        self.selectedDay,
        20,
        0
      ).getTime();
      if (self.checkInputs()) {
        var obj = {};
        obj.id_room = self.idRoom;
        obj.details = {};
        obj.details.id_emp = self.userToBook;
        obj.details.descr = self.descr;
        if (self.timeFormat == "12") {
          obj.details.start = new Date(
            self.selectedMonth +
            1 +
            "/" +
            self.selectedDay +
            "/" +
            self.selectedYear +
            " " +
            self.timeStartH +
            ":" +
            self.timeStartM +
            " " +
            self.modeStart
          ).getTime();
          obj.details.end = new Date(
            self.selectedMonth +
            1 +
            "/" +
            self.selectedDay +
            "/" +
            self.selectedYear +
            " " +
            self.timeEndH +
            ":" +
            self.timeEndM +
            " " +
            self.modeEnd
          ).getTime();
        } else {
          obj.details.start = new Date(
            self.selectedYear,
            self.selectedMonth,
            self.selectedDay,
            self.timeStartH,
            self.timeStartM
          ).getTime();
          obj.details.end = new Date(
            self.selectedYear,
            self.selectedMonth,
            self.selectedDay,
            self.timeEndH,
            self.timeEndM
          ).getTime();
        }
        if (obj.details.start > obj.details.end) {
          self.msg = "Start of event must be earlier than end";
          return;
        }
        if (
          obj.details.start < ds ||
          obj.details.end < ds ||
          obj.details.start > de ||
          obj.details.end > de
        ) {
          self.msg = "Avaliable time 8:00-20:00";
          return;
        }

        if (self.isReccuring) {
          var reccuring = {};
          reccuring.type = self.selectedRecurring;
          reccuring.duration = self.duration;
          obj.reccuring = reccuring;
        }

        var xhr = new XMLHttpRequest();
        var json = JSON.stringify(obj);
        xhr.open("POST", getUrl() + "events/", true);
        xhr.setRequestHeader(
          "Authorization",
          "Basic " + btoa(self.user.id + ":" + self.user.hash)
        );
        xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
        xhr.onreadystatechange = function() {
          if (xhr.readyState != 4) return;
          if (xhr.status != 200) {
            alert(xhr.status + ": " + xhr.statusText);
          } else {
            var res = JSON.parse(xhr.responseText);
            if (res === true) {
              self.msg = "Added";
            } else {
              self.msg = res;
            }
          }
          self.$parent.getEvents();
        };
        xhr.send(json);
      }
    },
    
    checkInputs: function() {
      var self = this;

      if (!self.userToBook) {
        self.msg = "Select User";
        return false;
      }
      if (
        !self.selectedYear ||
        !Number.isInteger(self.selectedMonth) ||
        !self.selectedDay
      ) {
        self.msg = "Select Day";
        return false;
      }
      if (
        !self.timeStartH ||
        !self.timeStartM ||
        !self.timeEndH ||
        !self.timeEndM
      ) {
        self.msg = "Check time fileds";
        return false;
      }
      if (!self.descr) {
        self.msg = "Check description field";
        return false;
      }
      if (self.isReccuring) {
        if (self.duration == "") {
          self.msg = "Duration required";
          return false;
        }

        if (!Number.isInteger(+self.duration) || +self.duration < 0) {
          self.msg = "Duration must be a positive integer";
          return false;
        }
        if (self.selectedRecurring == "weekly") {
          if (!Number.isInteger(+self.duration) || +self.duration > 3) {
            self.msg = "duration on Weekly type must be in (1-3)";
            return false;
          }
          if (self.selectedRecurring == "be-weekly") {
            if (!Number.isInteger(+self.duration) || +self.duration > 2) {
              self.msg = "duration on be-weekly type must be in (1-2)";
              return false;
            }
          }
          if (self.selectedRecurring == "monthly") {
            if (self.duration != "1") {
              self.msg = "on Monthly max duration is 1";
              return false;
            }
          }
        }
      }
      return true;
    },

    getEmployees: function() {
      var self = this;
      var xhr = new XMLHttpRequest();
      if (self.user.id_role == ADMIN) {
        xhr.open("GET", getUrl() + "users/", true);
      } else {
        xhr.open("GET", getUrl() + "users/" + self.user.id, true);
      }
      xhr.setRequestHeader(
        "Authorization",
        "Basic " + btoa(self.user.id + ":" + self.user.hash)
      );
      xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        if (xhr.status != 200) {
          alert(xhr.status + ": " + xhr.statusText);
        } else {
          var res = JSON.parse(xhr.responseText);
          if (res) {
            self.employees = res;
          } else {
            self.employees = [];
            self.msg = "No users Found";
          }
        }
      };
      xhr.send();
    }
  },

  created() {
    this.getEmployees();
  },
  computed: {
    yearSelector() {
      var self = this;
      var years = [];
      for (var i = 0; i < self.yearsInDropdown; i++) {
        years.push({
          value: new Date().getFullYear() + i,
          title: new Date().getFullYear() + i
        });
      }
      return years;
    },

    monthSelector() {
      var self = this;
      var currDate = new Date();
      var monthSelArr = [];
      var month = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ];
      if (currDate.getFullYear() != self.selectedYear) {
        month.forEach(function(month, key) {
          monthSelArr.push({ value: key, title: month });
        });
      }
      if (currDate.getFullYear() == self.selectedYear) {
        self.selectedMonth = "";
        for (var i = currDate.getMonth(); i <= 11; i++) {
          monthSelArr.push({ value: i, title: month[i] });
        }
      }
      return monthSelArr;
    },

    daySelector() {
      var self = this;
      var days = [];
      var selYM = new Date(self.selectedYear, self.selectedMonth);
      var daysInMonth = new Date(
        selYM.getFullYear(),
        selYM.getMonth() + 1,
        0
      ).getDate();
      if (
        selYM.getFullYear() == self.curtentDate.getFullYear() &&
        selYM.getMonth() == self.curtentDate.getMonth()
      ) {
        self.selectedDay = "";
        for (var i = self.curtentDate.getDate(); i <= daysInMonth; i++) {
          var d = new Date(self.selectedYear, self.selectedMonth, i);
          if (d.getDay() != 6 && d.getDay() != 0) {
            days.push({ value: i, title: i });
          }
        }
      } else {
        for (var i = 1; i <= daysInMonth; i++) {
          var d = new Date(self.selectedYear, self.selectedMonth, i);
          if (d.getDay() != 6 && d.getDay() != 0) {
            days.push({ value: i, title: i });
          }
        }
      }
      return days;
    },

    hoursSelector() {
      var self = this;
      var hours = [];
      if (self.timeFormat == "12") {
        for (var i = 1; i <= 12; i++) {
          hours.push({ value: i, title: i });
        }
      } else {
        for (var i = 8; i <= 20; i++) {
          hours.push({ value: i, title: i });
        }
      }
      return hours;
    },

    minutesSelector() {
      var self = this;
      var minutes = [];
      self.minutes.forEach(function(m) {
        minutes.push({ value: m, title: m });
      });
      return minutes;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.time {
  height: 30px;
  border-radius: 4px;
}

.book-it {
  margin-top: 20px;
}

.book-it a {
  font-size: 120%;
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
</style>
