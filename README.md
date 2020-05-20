## Janus

## Short Description.
Janus is a web application based on Laravel, Vue JS for the digital administration of information at the University.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

Vue is a progressive framework for building user interfaces.

## Changes.

1.0.0
- Install and configuration [Laravel](https://laravel.com/).
- Install and configuration of database link of [Postgresql](https://www.postgresql.org/) for Laravel.
- Install and configuration of [Vue js](https://vuejs.org/) for Laravel.
- Install and configuration of [Element-ui](https://element.eleme.io/#/es) and Language for use.
- Develop of first administrator template for the application.

1.1.0
- Add to querys for User and controller for manipulations of dataset.
- Add to views Login, Dashboard, Logout.
- Install and configuration of [Vuex](https://vuex.vuejs.org/) for views. 
- Install and configuration of [JWT Firebase](https://github.com/firebase/php-jwt) for security of users for application web.

1.2.0
- Develop Libraries: JWTFAuth.php, DynamicMenu; 
- Add to tables user, profiles and userprofiles to schema "app" to database.
- Add to static menu for user navigation.

1.2.1
- Create function on database userprofiles.
- Add to dynamic menu for user navigation.
- Firts impressions for use axios interceptors.
- Firts impressions for use middleware.

1.2.2
- Complete middleware VerifyJWTFtoken.php
- Complete axios.interceptors for Request and Response.

1.3.0
- Install and configuration vuex-persistedstate for persistence of users.
- Add to dynamic user to dynamic menu and user profile.

1.4.0
- Add to table "app.user" a column "gestion" for default year access to application.
- Add to option year to menu and user profile.
-Modify to Store, for object User a to array User for a much mantience to application.

1.5.0
- Add to first principal page to application not definitive.
- Add to missing views in frontend and rename other into database and table app.profiles.
- Add  new routes into backend.

1.5.1
- Add changes to principal page.

1.6.0
- Created DeliveryDocumentsController and model DeliveryDocuments.
- Install el-search-table-pagination.
- Import ElSearchTablePagination in app.js.
- Create the view DeliveryDocuments and EditDeiveryDocuments.
- Create the route EditDeliveryDocument. 

1.7.0
- Add to functions necesary to work table public.persons to GeneralController and model General
- Create the View Persons and add to route respective.
- Use to components table and pagination into View Persons.
- Use to library LengthAwarePaginator from Laravel to set data returns.



## Contributing

Thank you for considering contributing to the Application Web! The contribution guide can be found in the [Janus Documentacion](https://google.com.bo).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [viadhose@gmail.com](mailto:viadhose@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

