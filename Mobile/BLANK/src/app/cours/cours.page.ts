import { Component, OnInit } from '@angular/core';
import { MereService } from '../mere.service';

@Component({
  selector: 'app-cours',
  templateUrl: './cours.page.html',
  styleUrls: ['./cours.page.scss'],
})
export class CoursPage implements OnInit {

  constructor(private service: MereService) { }

  ngOnInit() {
    this.coursEtu()
  }
  cours:any[]=[]
  coursEtu() {
    this.service.coursEtu(localStorage.getItem('id')).subscribe((result:any) =>
  
    this.cours=result.data
    
    )
  }
}
