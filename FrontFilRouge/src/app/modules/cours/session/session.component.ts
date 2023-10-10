import { Component, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';
import { SessionService } from './session.service';

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

  events = [
    {
      event_date: new Date(2023, 3, 1),
      event_title: "Bapteme",
      event_theme: 'blue'
    },
    {
      event_date: new Date(2023, 3, 10),
      event_title: "Birthday",
      event_theme: 'red'
    },
    {
      event_date: new Date(2023, 3, 16),
      event_title: "Mariage",
      event_theme: 'green'
    }
  ];

  event_title: string = '';
  event_date: any = '';
  event_theme: string = 'blue';

  // themes = [
  //   {
  //     value: "blue",
  //     label: "Blue Theme"
  //   },
  //   {
  //     value: "red",
  //     label: "Red Theme"
  //   },
  //   {
  //     value: "yellow",
  //     label: "Yellow Theme"
  //   },
  //   {
  //     value: "green",
  //     label: "Green Theme"
  //   },
  //   {
  //     value: "purple",
  //     label: "Purple Theme"
  //   }
  // ];

  // openModal: boolean = false;

  form!: FormGroup
  ngOnInit(): void {
    this.initDate();
    this.getNoOfDays();
  }
  constructor(private coursService: SessionService, private fb: FormBuilder) {
    this.form = this.fb.group({
      date: [this.event_date],
      heure_debut: [''],
      heure_fin: [''],
      Type: [''],
      salle_id: [''],
      sessionClasseCours: this.fb.array([]),
    });
  }

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

  initDate() {
    let today = new Date();
    this.month = today.getMonth();
    this.year = today.getFullYear();
    this.event_date = new Date(this.year,this.month, today.getDate()).toDateString();
    // console.log(this.event_date);

    // Ajouter cet appel pour générer les numéros de jour
    this.getNoOfDays();
  }


  isToday(date: number) {
    const today = new Date();
    const d = new Date(this.year, this.month, date);
    return today.toDateString() === d.toDateString() ? true : false;
  }


  showModal(date: number) {


   //moins un car les mois sont indexés à partir de zéro
    const formattedDate = new Date(this.year, this.month - 1, date);

    const year = formattedDate.getFullYear();
    const month = (formattedDate.getMonth() + 1).toString(); // +1 car les mois vont de 0 à 11
    const day = formattedDate.getDate().toString();

    this.event_date = `${year}-${month}-${day}`;
  }


  addEvent() {
    if (this.event_title == '') {
      return;
    }

    this.events.push({
      event_date: this.event_date,
      event_title: this.event_title,
      event_theme: this.event_theme
    });

    console.log(this.events);

    // clear the form data
    this.event_title = '';
    this.event_date = '';
    this.event_theme = 'blue';

    // close the modal
    // this.openModal = false;
  }

  getNoOfDays() {
    console.log('getNoOfDays() called');
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
    console.log(this.form.value);

  }

  nextMonth() {
    if (this.month < 11) {
      this.month++;
      this.getNoOfDays();
    }
  }
  // Dans votre composant Angular
  isEventOnDate(event: any, year: any, month: any, date: any) {
    const eventDate = new Date(event.event_date);
    return (
      eventDate.getFullYear() === year &&
      eventDate.getMonth() === month &&
      eventDate.getDate() === date
    );
  }

}
