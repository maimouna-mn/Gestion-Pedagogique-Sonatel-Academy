import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CoursComponent } from './cours.component';
import { SessionComponent } from './session/session.component';
import { NotificationComponent } from './notification/notification.component';

const routes: Routes = [{ path: '', component: CoursComponent }, {
  path: "session", component: SessionComponent,
},
{path: "notification", component: NotificationComponent,
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CoursRoutingModule { }
