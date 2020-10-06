initSW();

const applicationServerPublicKey = $("meta[name=vapid_public_key]").attr('content');

function initSW() {
  if ('serviceWorker' in navigator && 'PushManager' in window) {
    console.log('Service Worker and Push is supported');

    navigator.serviceWorker.register('/js/push-notification/service-worker.js')
      .then(function(swReg) {
        console.log('Service Worker is registered', swReg);
        swReg.update();

        swRegistration = swReg;
        initializeUI();
      })
      .catch(function(error) {
        console.error('Service Worker Error', error);
      });
  } else {
    console.warn('Push messaging is not supported');
    pushButton.textContent = 'Push Not Supported';
  }
}

function initializeUI() 
{ // @todo This works but can be written better
  subscribeUser();

  // Set the initial subscription value
  swRegistration.pushManager.getSubscription()
    .then(function(subscription) {
      isSubscribed = !(subscription === null);

      if (isSubscribed) {
        console.log('User IS subscribed.');
      } else {
        console.log('User is NOT subscribed.');
      }
    });
}

function subscribeUser() {
  const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
  swRegistration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: applicationServerKey
  })
    .then(function(subscription) {
      console.log('User is subscribed.');
      console.log(JSON.stringify(subscription));

      storePushSubscription(subscription);
    })
    .catch(function(err) {
      console.log('Failed to subscribe the user: ', err);
    });
}

function urlB64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}


function storePushSubscription(pushSubscription) {
  const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

  fetch('/api/push', {
    method: 'POST',
    body: JSON.stringify(pushSubscription),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': token,
      'Authorization': 'Bearer ' + window.token,
    }
  })
    .then((res) => {
      return res.json();
    })
    .then((res) => {
      console.log(res)
    })
    .catch((err) => {
      console.log(err)
    });
}
