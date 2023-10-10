import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CoursComponent } from './cours.component';
import { SessionComponent } from './session/session.component';

const routes: Routes = [{ path: '', component: CoursComponent }, {
  path: "session", component: SessionComponent,
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CoursRoutingModule { }
