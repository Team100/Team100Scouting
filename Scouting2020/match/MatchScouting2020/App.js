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
import { LogIn } from './Screens/Auth/LogIn';
import { TeamSelect } from './Screens/Scout/TeamSelect';
const AppRouter = createSwitchNavigator(
  {
    LogIn: {screen: LogIn},
    Home: { screen: Home },
    Testing: {screen: TeamSelect}
  },
  {
    initialRouteName: 'Testing',
  }
);
const AppContainer = createAppContainer(AppRouter);
export default AppContainer;

