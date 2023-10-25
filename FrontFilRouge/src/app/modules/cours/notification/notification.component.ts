import { Component, OnInit } from '@angular/core';
import { CoursServiceService } from '../cours-c/cours-service.service';
import { SessionService } from '../session/session.service';
import { AuthServiceService } from '../../auth/auth-service.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-notification',
  templateUrl: './notification.component.html',
  styleUrls: ['./notification.component.css']
})
export class NotificationComponent implements OnInit {
  fonctionnalitesRp!: boolean;
  fonctionnalitesProf!: boolean;
  fonctionnalitesAttache!: boolean;
  constructor(private coursService: CoursServiceService, private sessionService: SessionService, private authService: AuthServiceService) { }

  bool:boolean=false
  listeNotifs!: any
  ngOnInit(): void {
    this.listeNotif()
    this.fonctionnalitesRp = this.authService.isRp();
    this.fonctionnalitesProf = this.authService.isProf();
    this.fonctionnalitesAttache = this.authService.isAttache();
  }

  listeNotif() {
    this.coursService.listeDemandeAnnulation().subscribe((result: any) => {
      this.listeNotifs = result.data;
      console.log(this.listeNotifs);
    })
  }

  annulerSession(id: number) {
    this.sessionService.SupprimerSession(id).subscribe((result) => {
      console.log(result);
      Swal.fire({
        title: 'Succès',
        text: 'Session annulee avec succès.',
        icon: 'success',
        confirmButtonText: 'OK'
      })
      this.bool=true

    })
  }
  logout() {
    this.authService.logout();
  }
}
