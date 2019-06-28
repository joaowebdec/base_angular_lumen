import { Injectable } from "@angular/core";
import { HttpClient } from '@angular/common/http';

import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export abstract class Service {

    constructor(protected http: HttpClient) {}

    public post(params: any, endPoint: string) : Promise<any> {
        return this.http.post(environment.api + endPoint, params).toPromise();
    }

    public get(endPoint: string) : Promise<any> {
        return this.http.get(environment.api + endPoint).toPromise();;
    }

    public put(params: any, endPoint: string) : Promise<any> {
        return this.http.put(environment.api + endPoint, params).toPromise();;
    }

    public delete(endPoint: string) : Promise<any> {
        return this.http.delete(environment.api + endPoint).toPromise();;
    }

    public getApi() : string {
        return environment.api;
    }    

}