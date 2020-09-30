import '_admin.scss';

declare global {
  interface Window {
    labels: {
      [key: string]: string
    },
    get_label: Function
  }
}

function main() {
  window.labels = window.labels || {};
  window.get_label = get_label;
  load_labels();
}

function load_labels() {
  const rest_auth_nonce = (document.getElementById('rest_auth_nonce') as HTMLFormElement).value;
  return jQuery.ajax({
    url: '/wp-json/admin/constants',
    headers: {
      'X-WP-Nonce': rest_auth_nonce
    },
    method: 'GET',
  }).done(function (data) {
    window.labels = data;
  })
}

interface CarbonValue {
  type: string,
  subtype: string,
  id: string
}

function get_label(obj: CarbonValue[], extra_label: string): string {
  let _return = '---';
  if (obj.length > 0) {
    const key = `${obj[0].type}:${obj[0].subtype}:${obj[0].id}`;
    if (window.labels[key]) {
      _return = window.labels[key];
      if (extra_label) {
        _return = _return + extra_label;
      }
    }
  }
  return _return;
}

main();