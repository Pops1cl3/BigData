import http from 'k6/http';
import { check } from "k6";

export let options = {
    stages: [
        // Ramp-up from 1 to 5 VUs in 5s
        { duration: "5s", target: 5 },
        // 10 VUs for 10s
        { duration: "10s", target: 10 },
        // 50 VUs for 10s
        { duration: "10s", target: 50 },
        // Ramp-down from 50 to 100 VUs for 5s
        { duration: "5s", target: 10 },
        // Ramp-down from 5 to 0 VUs for 5s
        { duration: "5s", target: 5 }
    ]
};
export default function () {
    var response = http.get("http://php:80/", {headers: {Accepts: "application/json"}});
    check(response, { "status is 200": (r) => r.status === 200 });

    var response = http.get("http://php:80?page=30/", {headers: {Accepts: "application/json"}});
    check(response, { "status is 200": (r) => r.status === 200 });

    var response = http.get("http://php:80/", {headers: {Accepts: "application/json"}});
    check(response, { "status is 200": (r) => r.status === 200 });
};