import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CoursRoutingModule } from './cours-routing.module';
import { CoursComponent } from './cours.component';
import { CoursCComponent } from './cours-c/cours-c.component';
import { HttpClientModule } from '@angular/common/http';
import { ModuleComponent } from './module/module.component';


@NgModule({
  declarations: [
    CoursComponent,
    CoursCComponent,
    ModuleComponent
  ],
  imports: [
    CommonModule,
    CoursRoutingModule,
    HttpClientModule
  ]
})
export class CoursModule { }
