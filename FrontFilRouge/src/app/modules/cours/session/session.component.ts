import { Component, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';
import { SessionService } from './session.service';
import { CoursServiceService } from '../cours-c/cours-service.service';
import Swal from 'sweetalert2';
import { AuthServiceService } from '../../auth/auth-service.service';

@Component({
  selector: 'app-session',
  templateUrl: './session.component.html',
  styleUrls: ['./session.component.css']
})
export class SessionComponent implements OnInit {
  month!: number;
  year!: number;
  no_of_days: number[] = [];

  blankdays: number[] = [];
  MONTH_NAMES = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];

  events: any[] =
    [
      {
        event_id: '',
        event_date: '',
        event_title: '',
        event_statut: '',
        event_Type: '',
        event_heure: '',
        event_prof: '',
        event_module: '',
        event_session_cours_classe_id: ''
      }]

  moduleSelectionnee!: number
  event_title: string = '';
  event_statut: string = '';
  event_date: any = '';
  event_Type: any = '';
  classeSelectionne!: number
  listeSessionClasse!: any[]
  form!: FormGroup
  moduleclasses!: any[]
  bool: boolean = false
  selectedCoursClasseId!: number | null;
  module!: any[]
  event_heure!: any
  event_prof!: any
  event_module!: any
  date!: number;
  selectedOption!: string;
  eventStatuts: string[] = [];

  ngOnInit(): void {
    this.index()
    this.initDate();
    this.getNoOfDays();
    this.profSessions()
  }

  fonctionnalitesRp!: boolean;
  fonctionnalitesProf!: boolean;
  fonctionnalitesAttache!: boolean;
  constructor(private sessionService: SessionService, private coursService: CoursServiceService, private fb: FormBuilder, private authService: AuthServiceService) {
    this.form = this.fb.group({
      date: [''],
      heure_debut: [''],
      heure_fin: [''],
      Type: [''],
      salle_id: [''],
      module: [''],
      sessionClasseCours:
        this.fb.array([
        ]),
    });
    this.fonctionnalitesRp = this.authService.isRp();
    this.fonctionnalitesProf = this.authService.isProf();
    this.fonctionnalitesAttache = this.authService.isAttache();
  }
  classes!: any[]
  salles!: any[]
  get sessionClasseCours() {
    return this.form.get("sessionClasseCours") as FormArray
  }

  ajouterLigne() {
    this.sessionClasseCours.push(
      this.fb.group({
        cours_classe_id: ['']
      })
    );
  }

  index() {
    this.sessionService.all().subscribe((result: any) => {
      this.salles = result.data1
      this.classes = result.data3

    })
  }

  initDate() {
    let today = new Date();
    this.month = today.getMonth();
    this.year = today.getFullYear();
    this.event_date = new Date(this.year, this.month, today.getDate()).toDateString();

    this.getNoOfDays();
  }



  showModal(date: number) {
    const formattedDate = new Date(this.year, this.month - 1, date);
    const year = formattedDate.getFullYear();
    const month = (formattedDate.getMonth() +2).toString(); 
    const day = formattedDate.getDate().toString();

    this.event_date = `${year}-${month}-${day}`;
    console.log(this.event_date);


  }

  getNoOfDays() {
    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
    this.no_of_days = [];
    for (let i = 1; i <= daysInMonth; i++) {
      this.no_of_days.push(i);
    }
  }

  prevMonth() {
    if (this.month > 0) {
      this.month--;
      this.getNoOfDays();
    }
  }

  add() {
    this.form.get('date')?.setValue(this.event_date)
    const newFormGroup = this.fb.group({
      cours_classe_id: [this.selectedCoursClasseId]
    });

    const sessionClasseCours = this.form.get('sessionClasseCours') as FormArray;
    sessionClasseCours.push(newFormGroup);


    this.sessionService.store(this.form.value).subscribe((result: any) => {

      // console.log(result);

      if (result.data) {
        this.form.reset()
        const session = result.data;
        const heureDebutParts = session.heure_debut.split(':');
        const heureFinParts = session.heure_fin.split(':');
        const heureDebut = `${heureDebutParts[0]}:${heureDebutParts[1]}`;
        const heureFin = `${heureFinParts[0]}:${heureFinParts[1]}`;

        this.events.unshift({
          event_date: new Date(session.date),
          event_title: session.libelle,
          event_Type: session.Type,
          event_prof: session.professeur,
          event_module: session.module,
          event_heure: `${heureDebut}-${heureFin}`
        });

        this.event_title = '';
        this.event_date = '';
      } else if (result.error) {
        Swal.fire({
          title: 'Erreur',
          text: "Une erreur s'est produite lors de l'ajout.",
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    },
    );
  }

  modalData!: any
  openModal(date: any) {
    const eventsForDate = this.events.filter(event => event == event.event_date);
    this.modalData = { date, events: eventsForDate };
  }


  nextMonth() {
    if (this.month < 11) {
      this.month++;
      this.getNoOfDays();
    }
  }


  heureSession1(event: any, year: any, month: any, date: any) {
    const eventDate = new Date(event.event_date);
    return (
      eventDate.getFullYear() === year &&
      eventDate.getMonth() === month &&
      eventDate.getDate() === date
    );
  }


  heureSession(events: any[], year: any, month: any, date: any) {
    const filteredEvents = events.filter((event) => {
      const eventDate = new Date(event.event_date);
      return (
        eventDate.getFullYear() === year &&
        eventDate.getMonth() + 1 === month &&
        eventDate.getDate() === date
      );
    });

    return filteredEvents.slice(0, 2);

  }

  getAllEvents(date: any) {
    return this.events.filter((event) => {
      const eventDate = new Date(event.event_date);
      return (
        eventDate.getFullYear() === this.year &&
        eventDate.getMonth() + 1 === this.month &&
        eventDate.getDate() === date
      );
    });
  }
  logout() {
    this.authService.logout();
  }

  selectedEvents!: any
  showAllEvents(date: any) {
    const selectedEvents = this.getAllEvents(date);
    this.selectedEvents = selectedEvents;
    console.log(selectedEvents);

  }

  filtreCours() {
    this.sessionService.filtre(this.classeSelectionne).subscribe((result: any) => {
      this.filtreModuleByClasse()
      this.listeSessionClasse = result.data2;
      this.events = [];
      this.events = this.listeSessionClasse.map(session => {
        const heureDebutParts = session.heure_debut.split(':');
        const heureFinParts = session.heure_fin.split(':');
        const heureDebut = `${heureDebutParts[0]}:${heureDebutParts[1]}`;
        const heureFin = `${heureFinParts[0]}:${heureFinParts[1]}`;

        return {
          event_id: session.id,
          event_session_cours_classe_id: session.session_cours_classe_id,
          event_date: new Date(session.date),
          event_title: session.salle_id ? session.salle_id.libelle : null,
          event_Type: session.Type,
          event_prof: session.professeur,
          event_statut: session.statut,
          event_module: session.module,
          event_heure: `${heureDebut}-${heureFin}`
        };
      });
    });
  }

  filtreModuleByClasse() {
    this.sessionService.filtre1(this.classeSelectionne).subscribe((result: any) => {
      this.module = result
    })
  }

  moduleClasse() {
    const module_id = this.form.get('module')?.value;

    this.coursService.filtre1(module_id).subscribe((result: any) => {
      this.moduleclasses = result.filter((item: any) => item.classe_id !== +this.classeSelectionne);

      const selectedModuleId = this.form.get('module')?.value;

      const selectedModuleClass = this.module.find(item => item.module.id === +selectedModuleId);

      if (selectedModuleClass) {
        this.selectedCoursClasseId = selectedModuleClass.cours_classe_id;
      }
    })
  }


  annulerSession(id: number, i: number) {
    this.sessionService.annnuler(id).subscribe((result: any) => {
      console.log(result);
      if (result.message) {
        this.eventStatuts[i] = 'Annulée';
      } else {
        Swal.fire({
          title: 'Erreur',
          text: "impossible d'annuler une session en cours",
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    })
  }

  validerSession(id: number, i: number) {
    this.sessionService.valider(id).subscribe((result: any) => {
      console.log(result);
      if (result.message) {
        this.eventStatuts[i] = 'validee';
      }
    })
  }

  invaliderSession(id: number, i: number) {
    this.sessionService.invalider(id).subscribe((result: any) => {
      console.log(result);
      if (result.message) {
        this.eventStatuts[i] = 'invalidee';
      }

    })
  }

  profSessions() {
    this.sessionService.profSessions(localStorage.getItem('id')).subscribe((result: any) => {
      console.log(result);
      this.events = result.sessions.map((session: any) => {

        const heureDebutParts = session.heure_debut.split(':');
        const heureFinParts = session.heure_fin.split(':');
        const heureDebut = `${heureDebutParts[0]}:${heureDebutParts[1]}`;
        const heureFin = `${heureFinParts[0]}:${heureFinParts[1]}`;

        return {
          event_id: session.id,
          event_session_cours_classe_id: session.session_cours_classe_id,
          event_date: new Date(session.date),
          event_title: session.salle_id ? session.salle_id.libelle : null,
          event_Type: session.Type,
          event_prof: session.professeur,
          event_statut: session.statut,
          event_module: session.module,
          event_heure: `${heureDebut}-${heureFin}`
        };
      });

    })
  }

  motifAnnulation: string = '';

  demandeAnnulation(id: number) {
    this.sessionService.DemandeAnnulation(id, this.motifAnnulation).subscribe((result) => {
      console.log(result);
      Swal.fire({
        title: 'Succès',
        text: 'Demande annulation envoyé avec succes avec succès.',
        icon: 'success',
        confirmButtonText: 'OK'
      })

    })
  }
}
