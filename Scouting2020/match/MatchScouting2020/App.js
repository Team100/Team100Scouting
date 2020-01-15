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
import {MatchScout} from './Screens/Scout/MatchScout';
const AppRouter = createSwitchNavigator(
  {
    LogIn: {screen: LogIn},
    Home: { screen: Home },
    Testing: {screen: MatchScout}
  },
  {
    initialRouteName: 'Testing',
  }
);
const AppContainer = createAppContainer(AppRouter);
export default AppContainer;

