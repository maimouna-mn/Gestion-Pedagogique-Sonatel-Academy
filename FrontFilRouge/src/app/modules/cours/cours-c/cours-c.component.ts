import { Component, OnInit } from '@angular/core';
import { CoursServiceService } from './cours-service.service';
import { ModuleService } from '../module/module.service';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';

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

  constructor(private coursService: CoursServiceService, private moduleService: ModuleService, private fb: FormBuilder) {
    this.form = this.fb.group({
      module: [null],
      prof_module_id: [''],
      semestre_id: [1],
      annee_scolaire_id: [1],
      heures_global: [''],
      classes: this.fb.array([]),
    });
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
  semestreSelectionne!: number
  // -----------------------------------------------------------------------------------------------------------------------------------
  get classes() {
    return this.form.get("classes") as FormArray
  }

  ajouterLigne() {
    this.classes.push(
      this.fb.group({
        classe_id: ['']
      })
    );
  }

  index() {
    this.coursService.all1(this.page).subscribe((result: any) => {
      this.listeCours = result.data
      this.totalPages = result.meta.last_page;
    })
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
      this.listeCours.unshift(result.data)
      this.form.reset()
    })
  }

  delete(id: number) {
    this.coursService.delete(id).subscribe((result) => {
      console.log(result);
      this.listeCours = this.listeCours.filter(cours => cours.id !== id);
    })
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

  recherche(){
    this.listeCours.filter((item=>item.libelle))
    console.log(this.listeCours);

    console.log(this.listeCours.filter((item=>item.moduleProf.module === "Laravel")));

  }

  filtreCours() {
    this.coursService.filtre(this.semestreSelectionne).subscribe((result: any) => {
      this.listeCours = result.data
    })
  }

}
