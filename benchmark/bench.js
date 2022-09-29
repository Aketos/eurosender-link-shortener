import http from 'k6/http';
import {check, sleep} from 'k6';

const vus = typeof __ENV.VUS !== "undefined" ? +__ENV.VUS : 10;
const delay = typeof __ENV.DELAY !== "undefined" ? +__ENV.DELAY : 1;

export let options = {
    vus: vus,
    duration: "10s",
    maxRedirects: "0",
};

export default function() {
    sleep(5*Math.random());
    let resShorten = http.get('http://eurosender.dev.localhost/api/v1/shorten?link=https://www.google.com/search?q=' + uuid());

    check(resShorten, { 'successfully shortened': (r) => r.status === 200 });
    sleep(delay);

    let resRedirect = http.get(JSON.parse(resShorten.body));
    check(resRedirect, { 'successfully redirected': (r) => r.status === 302 });
    sleep(delay);
}

function uuid() {
    return 'xxxxxxxx-xxxx-xxxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        let r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
