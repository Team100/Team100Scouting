import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";

import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'
import EventListener from 'react-event-listener';
import {message} from "antd";
import APIConnect from "../../../APIConnect";

export default class RunnerPage extends Component {
    configURL = APIConnect.SCOUTING_GENERATION_SCHEMA;
    state = {actions: [], timer: 150}; //TODO set to 150
    interval;

    tick() {
        var time = this.state.timer;
        if (time > 0) {
            this.setState({timer: time - 1});
            console.log(time);
        } else {
            clearInterval(this.interval);
            this.onComplete();

        }

    }

    onComplete() {
        this.props.callback({"actions": this.state.actions, "config": this.state.config.config});
    }


    componentWillMount() {
        this.tick = this.tick.bind(this);

        this.interval = setInterval(this.tick, 1000);
        document.title = "Field Scouting";

        this.updateConfig();
    }

    navigateToPage(page) {
        if (page == 0) {
            page = 10;
        }
        this.setState({page: page - 1});
        console.info("PAGE " + page);

    }

    updateConfig() {


        fetch(this.configURL)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                return data;
            })
            .then(data => this.setState({config: data}))
            .then(() => console.info("Logged"))
            .then(() => console.log(this.state));

    }

    handleKeyPress = (event) => {


        if (event.key == "Left WinKey" || event.key == "Meta" || event.key == "Command" || event.key == "Alt" || event.key == "Control") { //HANDLE Improper Key Presses
            //DO NOTHING
        } else if (!isNaN(event.key)) {
            this.navigateToPage(event.key);
        } else {
            this.processAction(event.key);
        }
    }

    processAction(key) {
        var pageID = this.state.page;
        var page = this.state.config.ui.pages[pageID];
        var actions = page.actions;
        var currentAction = actions[key];

        if (currentAction != undefined) {
            message.info(`Added: ${currentAction.name}`, 1);

            this.state.actions.push({time: this.state.timer, type: currentAction.id});
            var msg = new SpeechSynthesisUtterance(currentAction.name);
            window.speechSynthesis.speak(msg);
            console.log(this.state.actions);
        }


    }

    generateButtonRow(el) {
        var btnVal = el.btn;
        var elementConfig = this.state.config.ui.pages[this.state.page].actions[btnVal];

        return (
            <div className={"mapRow"} key={Math.random() * 9999 + 9999}>
                <div className={"mapEl"} onClick={() => this.processAction(btnVal)}>
                    <p>{elementConfig.name} ({btnVal})</p>
                </div>
            </div>
        );


    }

    generateButtonColumn(el) {
        var row = el.map((item) => this.generateButtonRow(item));

        return (
            <div className={"mapCol"} key={Math.random() * 9999}>
                {row}
            </div>
        );


    }

    generateButtonMap() {
        var pageconfig = this.state.config.ui.pages[this.state.page];
        if (pageconfig == undefined) {
            this.setState({page: this.state.page - 1});
        }
        var buttonconfig = pageconfig.layout;
        var map = buttonconfig.map((item) => this.generateButtonColumn(item));
        return (
            <div className={"map"}>
                {map}
            </div>
        )

    }

    render() {

        if (this.state.config == null) {
            return (<p>Loading</p>);
        } else if (this.state.page == null) {
            this.navigateToPage(1);
            return (<p>Warning: Page Index Out of Bounds. Autocorrecting</p>);

        } else if (this.state.page > (this.state.config.ui.pages.length) + 1) {
            this.navigateToPage(this.state.page - 1);
            return (<p>Loading</p>);

        } else {
            var menuItems = this.state.config.ui.pages;

            var menu = menuItems.map((item) =>
                <div className={"sidebarItem"} onClick={() => this.navigateToPage(item.btn)}><a key={item.btn}
                                                                                                className={"sidebarButton"}>{item.name} ({item.btn})</a>
                </div>
            );
            var buttonMap = this.generateButtonMap();
            return (

                <div className={"page"}>
                    <EventListener onKeyDown={this.handleKeyPress} target="window"/>
                    <div className={"sidebar"}>
                        <div className={"sidebarItem"}>
                            <h3>{this.state.timer}</h3>
                        </div>

                        {menu}
                    </div>
                    <div className={"pageContent"}>
                        {buttonMap}
                    </div>

                </div>


            );

        }


    }


}
