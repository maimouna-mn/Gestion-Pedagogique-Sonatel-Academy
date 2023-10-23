import { Component, OnInit } from '@angular/core';
import { CoursServiceService } from './cours-service.service';
import { ModuleService } from '../module/module.service';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';
import { AuthServiceService } from '../../auth/auth-service.service';
import Swal from 'sweetalert2';
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

  fonctionnalitesRp!: boolean;
  fonctionnalitesProf!: boolean;
  fonctionnalitesAttache!: boolean;
  constructor(private coursService: CoursServiceService, private moduleService: ModuleService, private fb: FormBuilder, private authService: AuthServiceService) {
    this.form = this.fb.group({
      module: [null],
      prof_module_id: [''],
      semestre_id: [''],
      annee_scolaire_id: [1],
      classes: this.fb.array([]),
    });
    this.fonctionnalitesRp = this.authService.isRp();
    this.fonctionnalitesProf = this.authService.isProf();
    this.fonctionnalitesAttache = this.authService.isAttache();
  }
  // -----------------------------------------------------------------------------------------------------------------------------------
  form!: FormGroup
  selectedModule!: any;
  filterProf!: any[];
  semestres!: any[];
  listeCours: any[] = []
  moduleProf!: any[]
  Classes!: any[]
  prof!: any[]
  page = 1;
  totalPages = 1;
  semestreSelectionne: number = 0
  etat!: number
  // -----------------------------------------------------------------------------------------------------------------------------------
  get classes() {
    return this.form.get("classes") as FormArray
  }

  ajouterLigne() {
    this.classes.push(
      this.fb.group({
        classe_id: [''],
        heures_global: ['']
      })
    );
  }

  removeClassField(index: number) {
    this.classes.removeAt(index);
  }

  formaterHeures(heures_global: string): string {
    const heures = parseInt(heures_global, 10);
    return `${heures}h`;
  }

  index() {
    if (this.fonctionnalitesRp) {
      this.coursService.all1(this.page).subscribe((result: any) => {
        this.listeCours = result.data
        this.totalPages = result.meta.last_page;
      })
    } else if (this.fonctionnalitesProf) {
      const id = localStorage.getItem("id");
      this.coursService.coursprof(this.page, id).subscribe((result: any) => {

        console.log(result);
        this.listeCours = result.data
        this.totalPages = result.meta.last_page;
      })
    }
  }

  all() {
    this.moduleService.all().subscribe((result: any) => {
      this.moduleProf = result.data1;
      this.prof = result.data1[0].professeurs
      this.Classes = result.data2
      this.semestres = result.data3
    })
  }

  filterProfessors() {
    const selectedModule = this.form.get('module')?.value;
    const selectedModuleData = this.moduleProf.find(module => module.id === +selectedModule);
    this.filterProf = selectedModuleData ? selectedModuleData.professeurs : [];
  }


  add() {
    console.log(this.form.value);
    this.coursService.store(this.form.value).subscribe((result: any) => {
      Swal.fire({
        title: 'Succès',
        text: 'Les données ont été insérées avec succès.',
        icon: 'success',
        confirmButtonText: 'OK'
      })

      this.listeCours.unshift(result.data);
      this.form.reset();
    },
      (error) => {
        Swal.fire({
          title: 'Erreur',
          text: "Une erreur s'est produite lors de l'insertion.",
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    );
  }


  delete(id: number) {
    this.coursService.delete(id).subscribe(
      (result: any) => {
        Swal.fire({
          title: 'Succès',
          text: 'Les données ont été supprimes avec succès.',
          icon: 'success',
          confirmButtonText: 'OK'
        })
        this.listeCours = this.listeCours.filter(cours => cours.id !== id);
      },

    )
  }

  previousPage() {
    if (this.page > 1) {
      this.page--;
      this.index();
    }
  }

  nextPage() {
    if (this.page < this.totalPages) {
      this.page++;
      this.index();
    }
  }

  recherche() {
    this.listeCours.filter((item => item.libelle))
    console.log(this.listeCours.filter((item => item.moduleProf.module === "Laravel")));

  }

  // filtreCoursSemestre() {
  //   this.coursService.filtre(this.semestreSelectionne).subscribe((result: any) => {
  //     this.listeCours = result.data
  //   })
  // }
  listeCours1: any[] = []; // Variable pour stocker les résultats de la recherche


  noResults: boolean = false;

  filtreCoursModule(e: Event) {
    const input = e.target as HTMLInputElement;
    const searchTerm = input.value;

    if (searchTerm) {
      const filteredCourses = this.listeCours.filter(course => course.moduleProf.module === searchTerm);
      if (filteredCourses.length > 0) {
        this.listeCours1 = filteredCourses;
        this.noResults = false;
      } else {
        this.listeCours1 = [];
        this.noResults = true;
      }
    } else {
      this.listeCours1 = [];
      this.noResults = false;
    }
  }



  filtreCoursEtat() {
    if ((this.fonctionnalitesRp)) {

      this.coursService.filtreEtatCours(this.etat).subscribe((result: any) => {
        this.listeCours = result.data
      })
    }
  }
  allSemestre() {
    this.coursService.semestreAll().subscribe((result) => {
      console.log(result);

    })
  }

  logout() {
    this.authService.logout();
  }
  detailcours!: any
  detailcours1!: any

  detailCours(id: number) {
    this.coursService.detailCours(id).subscribe((result: any) => {
      console.log(result);
      this.detailcours = result.data1
      this.detailcours1 = result.data2
    })
  }
}
