var events = require('events');




var bus = new events.EventEmitter();
bus.off = function(){
    return this.removeListener.apply(this, arguments);
}.bind(bus);



module.exports = bus;
