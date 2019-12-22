/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React from 'react';
import { createAppContainer, createSwitchNavigator } from 'react-navigation';
import { Home } from './Screens/Home';
const AppRouter = createSwitchNavigator(
  {
    Home: { screen: Home },
  },
  {
    initialRouteName: 'Home',
  }
);
const AppContainer = createAppContainer(AppRouter);
export default AppContainer;

