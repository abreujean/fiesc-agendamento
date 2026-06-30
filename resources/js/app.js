import Alpine from 'alpinejs';
import './api.js';
import { appLayout } from './components/appLayout.js';
import { alertsData } from './components/alertsData.js';
import { loginForm } from './components/loginForm.js';
import { dashboardData } from './components/dashboardData.js';
import { usersData } from './components/usersData.js';
import { createUserForm } from './components/createUserForm.js';
import { editUserForm } from './components/editUserForm.js';
import { availabilitiesData } from './components/availabilitiesData.js';
import { createAvailabilityForm } from './components/createAvailabilityForm.js';
import { appointmentsData } from './components/appointmentsData.js';

window.appLayout = appLayout;

Alpine.data('alertsData', alertsData);
Alpine.data('loginForm', loginForm);
Alpine.data('dashboardData', dashboardData);
Alpine.data('usersData', usersData);
Alpine.data('createUserForm', createUserForm);
Alpine.data('editUserForm', editUserForm);
Alpine.data('availabilitiesData', availabilitiesData);
Alpine.data('createAvailabilityForm', createAvailabilityForm);
Alpine.data('appointmentsData', appointmentsData);

window.Alpine = Alpine;
Alpine.start();
