import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PitScoutPage } from './pit-scout.page';

describe('PitScoutPage', () => {
  let component: PitScoutPage;
  let fixture: ComponentFixture<PitScoutPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PitScoutPage ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PitScoutPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
