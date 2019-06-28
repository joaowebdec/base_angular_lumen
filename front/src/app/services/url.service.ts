import { Injectable } from "@angular/core";

@Injectable({
    providedIn: 'root'
})
export class UrlService {

    jsonToQuery(obj: any) : string {

        let str = [];
        for (var p in obj) {
            if (obj.hasOwnProperty(p))
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }

        return str.join("&");

    }

}