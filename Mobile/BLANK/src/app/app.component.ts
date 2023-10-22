import { Component, OnInit } from '@angular/core';
import { MereService } from './mere.service';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent implements OnInit {
  public appPages = [
    { title: 'Cours', url: '/cours', icon: 'school' }, 
    { title: 'Sessions', url: '/session', icon: 'calendar'  },
  ];
  
  constructor(private service: MereService) { }
  ngOnInit(): void {
    this.user()
  }
  name!:any
  photo!:any
  user() {
    const storedName = localStorage.getItem('name');
    const storedphoto = localStorage.getItem('photo');
  
    if (storedphoto !== null && storedName !== null) {
      this.name = storedName;
      this.photo=storedphoto
    }
  }
  
}
