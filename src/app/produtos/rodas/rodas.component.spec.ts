import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RodasComponent } from './rodas.component';

describe('RodasComponent', () => {
  let component: RodasComponent;
  let fixture: ComponentFixture<RodasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RodasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RodasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
