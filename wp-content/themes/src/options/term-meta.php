<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

Container::make('term_meta', 'Location Perks')
  ->where('term_taxonomy', '=', 'location')
  ->add_fields(array(
    Field::make('checkbox', 'has_perks', 'Has Perks?'),
    Field::make('complex', 'perks', 'Perks')
      ->set_conditional_logic(array(
        array(
          'field' => 'has_perks',
          'value' => true,
        )
      ))
      ->set_layout('tabbed-vertical')
      ->add_fields('individual_perk', 'Perk', array(
        Field::make('text', 'perk_title', 'Title'),
        Field::make('rich_text', 'perk_description', 'Description'),
      ))
      ->set_header_template('
        <% if (perk_title) { %>
          <%- perk_title %>
        <% } else { %>
          ---
        <% } %>
      ')
  ));
