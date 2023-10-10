import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CoursRoutingModule } from './cours-routing.module';
import { CoursComponent } from './cours.component';
import { CoursCComponent } from './cours-c/cours-c.component';
import { HttpClientModule } from '@angular/common/http';
// import { ModuleComponent } from './module/module.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SessionComponent } from './session/session.component';
import { CalendarModule, DateAdapter } from 'angular-calendar';
import { adapterFactory } from 'angular-calendar/date-adapters/date-fns';
// import { NgbModalModule } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [
    CoursComponent,
    CoursCComponent,
    SessionComponent,
    // NgbCalendarModule
    // ModuleComponent
  ],
  imports: [
    CommonModule,
    CoursRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
    FormsModule,
    // FlatpickrModule.forRoot(),

    // NgbModalModule,
    CalendarModule.forRoot({
      provide: DateAdapter,
      useFactory: adapterFactory,
    }),
  ]
})
export class CoursModule { }
