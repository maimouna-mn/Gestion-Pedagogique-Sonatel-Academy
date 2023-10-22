import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CoursPage } from './cours.page';

describe('CoursPage', () => {
  let component: CoursPage;
  let fixture: ComponentFixture<CoursPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(CoursPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
