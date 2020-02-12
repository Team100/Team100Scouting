import React, {Component} from "react";

import "material-design-lite/material.min";
import "material-design-lite/material.min.css";
import "../../assets/css/materialIcons.css";
import EventListener, {withOptions} from 'react-event-listener';

export default class RunnerPage extends Component{
    configURL = "https://storage.googleapis.com/alpha.cdn.atco.mp/ScoutingGenerationSchema.json";


    componentDidMount() {
       this.updateConfig()
    }

    updateConfig(){
        fetch(this.configURL)
            .then(response => response.json())
            .then(data => this.setState({ config:data }))
            .then(console.log(this.state));

    }
    handleKeyPress = (event) => {
        console.log(event.key);
    }

    render(){
        return(
            <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
                <EventListener target={"window"} onKeyPress={this.handleKeyPress}/>

                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">
                        <div class="mdl-layout-spacer"></div>

                    </div>
                </header>
                <div class="mdl-layout__drawer">
                    <span class="mdl-layout-title">Match Analysis</span>
                    <nav class="mdl-navigation">
                        <a class="mdl-navigation__link" href="">Start/Stop Match</a>
                        <a class="mdl-navigation__link" href="">Power Cell</a>
                        <a class="mdl-navigation__link" href="">Color Wheel</a>
                        <a class="mdl-navigation__link" href="">End Game</a>
                        <a class="mdl-navigation__link" href="">Pushing Match</a>

                    </nav>
                </div>
                <main class="mdl-layout__content">
                    <div class="page-content"></div>
                </main>
            </div>

        );
    }



}
