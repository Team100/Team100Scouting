import React from "react";
import { createStackNavigator } from "react-navigation-stack";
import {More} from "./More";



const MoreRouter = createStackNavigator(
    {
        MoreMain: {screen: More}
    },
    {
        initialRouteName: 'MoreMain',



    }
);
export default MoreRouter;
