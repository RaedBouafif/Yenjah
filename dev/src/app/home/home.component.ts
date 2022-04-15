import { Component, OnInit } from '@angular/core';
import { AppService } from '../app.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private appService : AppService) { }

  ngOnInit(): void {
    this.getUsers()
  }
  loadingForPosts=false
  getUsers(){
    this.loadingForPosts=true
    this.appService.getData("/getPost.php?generalType=informatique").then((res)=>{
      this.usersData=res.data
      this.loadingForPosts=false
    })
  }
  usersData=[]
}
