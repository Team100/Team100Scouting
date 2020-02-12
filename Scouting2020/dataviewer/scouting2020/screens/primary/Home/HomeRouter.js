import React from "react";
import { createStackNavigator } from "react-navigation-stack";
import {Home} from "./Home";



const HomeRouter = createStackNavigator(
    {
        Home: {screen: Home}
    },
    {
        initialRouteName: 'Home',



    }
);
export default HomeRouter;
