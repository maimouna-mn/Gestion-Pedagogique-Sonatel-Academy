import { Component, OnInit } from '@angular/core';
import { CoursServiceService } from './cours-service.service';
import { ModuleService } from '../module/module.service';

@Component({
  selector: 'app-cours-c',
  templateUrl: './cours-c.component.html',
  styleUrls: ['./cours-c.component.css']
})
export class CoursCComponent implements OnInit {

  ngOnInit(): void {
    this.index()
    this.all()
  }

  constructor(private coursService: CoursServiceService, private moduleService: ModuleService) { }
  listeCours!: any[]
  moduleProf!: any[]

  index() {
    this.coursService.all().subscribe((result: any) => {
      console.log(result.data[0].moduleProf.module);
      this.listeCours = result.data
    })
  }

  all() {
    this.moduleService.all().subscribe((result: any) => {
      this.moduleProf = result;
      console.log(this.moduleProf);

    })
  }


}
