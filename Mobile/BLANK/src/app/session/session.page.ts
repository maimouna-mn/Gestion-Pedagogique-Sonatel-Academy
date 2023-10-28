import { Component, OnInit, ViewChild } from '@angular/core';
import { registerLocaleData } from '@angular/common';
import localeFr from '@angular/common/locales/fr';
import { MereService } from '../mere.service';
registerLocaleData(localeFr, 'fr');
@Component({
  selector: 'app-session',
  templateUrl: './session.page.html',
  styleUrls: ['./session.page.scss'],
})
export class SessionPage implements OnInit {


  calendar: { day: number, event: any }[][] = [];

  ngOnInit(): void {
    this.sessions()
  }
  bool: boolean = false

  sessionsE!: any[]
  currentYear: number;
  currentMonth: number;
  monthNames: string[] = [
    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
    'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
  ];

  constructor(private service: MereService) {
    const currentDate = new Date();
    this.currentYear = currentDate.getFullYear();
    this.currentMonth = currentDate.getMonth() + 1;
    this.initializeCalendar(this.currentYear, this.currentMonth);
  }

  selectedEvent!: { date: string, details: any };

  events: any[] = [
    {
      date: '',
      details: {
        heureD: '',
        heure: '',
        salle: '',
        professeur: '',
        module: '',
        statut: '',
      }
    }
  ];

  hasEvent(date: string): boolean {
    return this.events.some(event => event.date === date);
  }


  initializeCalendar(year: number, month: number) {
    const firstDayOfMonth = new Date(year, month - 1, 1);
    const lastDayOfMonth = new Date(year, month, 0);
    const numberOfDaysInMonth = lastDayOfMonth.getDate();
    const firstDayOfWeek = firstDayOfMonth.getDay();

    this.calendar = [];

    let currentDay = 1;

    for (let week = 0; week < 6; week++) {
      let weekArray: { day: number, event: any }[] = [];

      for (let dayOfWeek = 0; dayOfWeek < 7; dayOfWeek++) {
        if (week === 0 && dayOfWeek < firstDayOfWeek) {
          weekArray.push({ day: 0, event: null });
        } else if (currentDay > numberOfDaysInMonth) {
          weekArray.push({ day: 0, event: null });
        } else {
          const date = new Date(year, month - 1, currentDay);
          const formattedDate = date.toISOString().split('T')[0];
          const event = this.events.find((e) => e.date === formattedDate);

          weekArray.push({ day: currentDay, event: event });
          currentDay++;
        }
      }

      this.calendar.push(weekArray);
    }

  }

  previousMonth() {
    this.currentMonth--;
    if (this.currentMonth < 1) {
      this.currentMonth = 12;
      this.currentYear--;
    }
    this.initializeCalendar(this.currentYear, this.currentMonth);
  }

  nextMonth() {
    this.currentMonth++;
    if (this.currentMonth > 12) {
      this.currentMonth = 1;
      this.currentYear++;
    }
    this.initializeCalendar(this.currentYear, this.currentMonth);
  }

  getMonthName(monthNumber: number) {
    return this.monthNames[monthNumber - 1];
  }

  selectEvent(event: { date: string, details: any }): void {
    this.selectedEvent = event;
  }

  sessions() {
    this.service.sessionsEleve(localStorage.getItem("id")).subscribe((result) => {
      console.log(result.sessions.data2);
      this.sessionsE = result.sessions.data2;
      this.events = this.sessionsE.map(session => {
        const heureDebutParts = session.heure_debut.split(':');
        const heureFinParts = session.heure_fin.split(':');
        const heureDebut = `${heureDebutParts[0]}:${heureDebutParts[1]}`;
        const heureFin = `${heureFinParts[0]}:${heureFinParts[1]}`;

        return {
          date: session.date,
          details: {
            heureD: heureDebut,
            heure: heureDebut + "~" + heureFin,
            salle: session.salle_id ? session.salle_id.libelle : null,
            professeur: session.professeur,
            module: session.module,
            statut: session.statut,
            session_cours_classe_id: session.session_cours_classe_id
          }
        };
      });
    });
  }

  heureSession(date: any) {
    return this.events.filter(event => {
      const eventDate = event.date;
      return (
        eventDate === date
      );
    });
  };

  isCheckboxVisible(item: any) {
    if (item.details.statut === 'en_cours') {
      const now = new Date();
      const heureDebut = new Date(item.date + 'T' + item.details.heureD);
      const diffMinutes = -((heureDebut.getTime() - now.getTime()) / 1000 / 60);

      return diffMinutes >= 0 && diffMinutes <= 30;
    }
    return false;
  }

  emarger(item: any) {
    const userId =localStorage.getItem("id");
    
    if (item.user.id == userId) {
      return true;
    }
    return false;
  }
  
  emargement(idS: number) {
    // if (!this.bool) {
      console.log(idS,localStorage.getItem("inscription"));
      
      this.service.emargement(localStorage.getItem("inscription"), idS).subscribe((result) => {

        console.log(result);
        // this.bool = true;
      });
    // }
  }
  eleves!: any[]
  isModalOpen = false;
  listeEleves(isOpen: boolean, idS: number) {
    this.isModalOpen = isOpen;
    this.service.listeEleves(idS).subscribe((result) => {
      this.eleves = result
      console.log(this.eleves);

    });
  }

  setOpen(isOpen: boolean) {
    this.isModalOpen = isOpen;

  }
}

