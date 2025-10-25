import Sortable from 'sortablejs';
window.Sortable = Sortable;
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';


/** window.Echo.channel('notifications')
  .listen('.new-notification', (e) => {
    alert('📢 Thông báo mới: ' + e.message)
    console.log('📢 Event nhận được:', e)
  })
 */

