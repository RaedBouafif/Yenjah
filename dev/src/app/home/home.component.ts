import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { AppService } from '../app.service';
import { LocalStorageService } from '../local-storage-service.service';
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit  {

  constructor(private appService : AppService, private cookies : CookieService, private locStorage : LocalStorageService) { }

  ngOnInit(): void {
    this.getPosts()
      this.locStorage.watchStorage().subscribe((data:string)=>{
        this.search=localStorage.getItem("search")||""
        this.types=localStorage.getItem("typesPosts")?.split(",") ||[]
        this.filterData()
    })
  }
  types=localStorage.getItem("typesPosts")?.split(",") ||[]
  search=localStorage.getItem("search")||""
  loadingForPosts=false
  interval : any
  usersData=[]
  originData  : any
  filterData(){
    if(!this.search)
    {
      this.usersData=this.originData
    }
    else 
    {
      this.usersData=this.originData.filter((element:any)=>element.titre.indexOf(this.search)!=-1)
    }
  }
  getPosts(){
      this.loadingForPosts=true
      var idProfileIfExist=this.cookies.get("clid") ||  ""
      var data=JSON.stringify(this.types);
      this.appService.getData(`/getInitPost.php?types=${data}&idProfile=${idProfileIfExist}`).then((res)=>{
      this.usersData=res.data
      console.log(res.data)
      this.originData=res.data
      this.loadingForPosts=false
      this.filterData()
    })
  }
}
