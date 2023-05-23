function Counter(options) {
    this.timer;
    var instance = this;
    this.seconds = options.seconds || 10;
    this.onUpdateStatus = options.onUpdateStatus || function() {};
    this.onCounterEnd = options.onCounterEnd || function() {};
    this.onCounterStart = options.onCounterStart || function() {};

    this.decrementCounter = function() {
        instance.onUpdateStatus(instance.seconds);
        if (instance.seconds === 0) {
            clearInterval(instance.timer);
            instance.stopCounter();
            instance.onCounterEnd();
            return;
        }
        instance.seconds--;
    };

    this.startCounter = function() {
        console.log(instance.seconds);
        instance.onCounterStart();
        clearInterval(instance.timer);
        instance.timer = 0;
        instance.decrementCounter();
        instance.timer = setInterval(instance.decrementCounter, 1000);
    };

    this.stopCounter = function() {
        clearInterval(instance.timer);
    };
};

function Counter2(options) {
    this.timer2;
    var instance = this;
    this.seconds = options.seconds || 10;
    this.onUpdateStatus = options.onUpdateStatus || function() {};
    this.onCounterEnd = options.onCounterEnd || function() {};
    this.onCounterStart = options.onCounterStart || function() {};

    this.decrementCounter = function() {
        instance.onUpdateStatus(instance.seconds);
        if (instance.seconds === 0) {
            clearInterval(instance.timer2);
            instance.stopCounter();
            instance.onCounterEnd();
            return;
        }
        instance.seconds--;
    };

    this.startCounter = function() {
        console.log(instance.seconds);
        instance.onCounterStart();
        clearInterval(instance.timer2);
        instance.timer2 = 0;
        instance.decrementCounter();
        instance.timer2 = setInterval(instance.decrementCounter, 1000);
    };

    this.stopCounter = function() {
        clearInterval(instance.timer2);
    };
};

var optionsCounter = {
    // number of seconds to count down
    seconds: 599,

    onCounterStart: function () { 
    },

    // callback function for each second
    onUpdateStatus: function(second) {
        minutos = Math.floor(second / 60);
        seconds = second - minutos * 60;
        totaltime = "0"+minutos+":"+seconds
        $("#tiempoRestante").html(totaltime);
    },

    // callback function for final action after countdown
    onCounterEnd: function(opendata = true) {
        if (opendata) {
            showModal(this); 
        }
    }
};

var countdown = new Counter(optionsCounter);

countdown.startCounter();



function showModal(data){
    countdown = null;
    var secondTimer = new Counter2({
        // number of seconds to count down
        seconds: 60,

        onCounterStart: function () { 
            console.log("start2")
        },

        // callback function for each second
        onUpdateStatus: function(second) {
            $("#timerDataUserSession").html(second)
        },

        // callback function for final action after countdown
        onCounterEnd: function() {
            location.href = root + "users/logout";
        }
    });

    setTimeout(function() {
        secondTimer.startCounter();
    }, 1000);

    Swal.fire({
      title: 'Su sesión termina en 1 minuto',
      html:
        '<span id="timerDataUserSession"></span>',
      showCancelButton: true,
      confirmButtonText: `Continuar sesión`,
      cancelButtonText: 'Cerrar'
    }).then((result) => {
      if (result.isConfirmed) {
        secondTimer.stopCounter();
        var countdown = new Counter(optionsCounter);
        countdown.stopCounter();
        countdown.startCounter();
      } 
    })
}

