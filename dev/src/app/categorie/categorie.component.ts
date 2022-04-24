import { Component, OnInit } from '@angular/core';
import { AppService } from '../app.service';
import { LocalStorageService } from '../local-storage-service.service';

@Component({
  selector: 'app-categorie',
  templateUrl: './categorie.component.html',
  styleUrls: ['./categorie.component.css']
})
export class CategorieComponent implements OnInit {
  
  constructor(private appService:AppService , private locStorage : LocalStorageService) { }

  ngOnInit(): void {
    this.getCategories()
  }
  categories  = {}
  keys = Object.keys(this.categories)
  getCategories(){
    this.appService.getData("/types.php?allTypes=all").then((res)=>{
      this.categories=res.data
    })
  }
  types:string[]= localStorage.getItem("typesPosts")?.split(",") ||[]
  handleCheck(sous:string){
    var filter=this.types.filter((element : string)=>element===sous)
    filter.length===0 ? this.types.push(sous) : this.types=this.types.filter((element : string)=>element!==sous)
    this.locStorage.setItem("typesPosts",this.types)
  }
  verifyCheck(sous : string){
    if(localStorage.getItem("typesPosts")!=null)
    {
      if(localStorage.getItem("typesPosts")?.indexOf(sous)!=-1) return true 
      else return false
    }
    else 
    {
      return false
    }
  }
}
