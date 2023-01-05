import { Injectable } from '@angular/core';
import axios from "axios";
@Injectable({
  providedIn: 'root'
})
export class AppService {

  constructor() { }
  url="http://localhost/yenjah.tn/yenjah.tn/dev/backend"
  getData(pathUrl : string){
    return axios.get(this.url+pathUrl)
  }
  sendData(pathUrl : string, data : any){
    return axios.post(this.url+pathUrl,data)
  }
}
