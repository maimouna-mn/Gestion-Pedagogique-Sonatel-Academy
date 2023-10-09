import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { ModuleService } from './module.service';

@Component({
  selector: 'app-module',
  templateUrl: './module.component.html',
  styleUrls: ['./module.component.css']
})
export class ModuleComponent implements OnInit {
  ngOnInit(): void {
  
  }
  constructor(private moduleService: ModuleService) {
  }


}
