import React, {Component} from "react";

import "material-design-lite/material.min";
import "material-design-lite/material.min.css";
import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import EventListener, {withOptions} from 'react-event-listener';

export default class RunnerPage extends Component{
    configURL = "https://storage.googleapis.com/alpha.cdn.atco.mp/ScoutingGenerationSchema.json";

    state={};

    componentWillMount() {
       this.updateConfig()
    }

    navigateToPage(page){
        if(page == 0){
            page = 10;
        }
        this.setState({page:page-1});
        console.info("PAGE "+page);

    }
    updateConfig(){

        fetch(this.configURL)
            .then(response => response.json())
            .then(data => {console.info(data); return data;})
            .then(data => this.setState({ config : data }))
            .then(() => console.info("Logged"))
            .then(() => console.log(this.state));

    }
    handleKeyPress = (event) => {
        console.log(event.key);

        var isNumber = !isNaN(event.key);
        if(isNumber){
            this.navigateToPage(event.key);
        }
        else{
            this.processAction(event.key);
        }
    }

    processAction(key){
        var pageID = this.state.page;
        var page = this.state.config.ui.pages[pageID];
        var actions = page.actions;
        var currentAction = actions[key];
        alert(currentAction.id);

    }

    generateButtonRow(el){
        var btnVal = el.btn;
        var elementConfig = this.state.config.ui.pages[this.state.page].actions[btnVal];
        console.log("EL CONFIG");
        console.log(elementConfig);
        console.log(elementConfig.id);
        return(
            <div className={"mapRow"} key={Math.random()*9999+9999}>
                <div className={"mapEl"}>
                    <p>{elementConfig.name} ({btnVal})</p>
                </div>
            </div>
            );


    }
    generateButtonColumn(el){
        var row = el.map((item)=>this.generateButtonRow(item));

        return(
            <div className={"mapCol"} key={Math.random()*9999}>
                {row}
            </div>
        );



    }
    generateButtonMap(){
        var pageconfig = this.state.config.ui.pages[this.state.page];
        if(pageconfig == undefined){
            this.setState({page:this.state.page-1});
        }
        var buttonconfig = pageconfig.layout;
        var map = buttonconfig.map((item)=>this.generateButtonColumn(item));
        console.log("Generating Map");
        return(
            <div className={"map"}>
                {map}
            </div>
        )

    }

    render(){

         if(this.state.config == null) {
            return (<p>Loading</p>);
        }
        else if(this.state.page == null){
            this.navigateToPage(1);
             return (<p>Loading</p>);

         }
        else if(this.state.page > (this.state.config.ui.pages.length)+1){
            this.navigateToPage(this.state.page - 1);
             return (<p>Loading</p>);

         }


        else{
                var menuItems = this.state.config.ui.pages;

                var menu = menuItems.map((item)=>
                    <div className={"sidebarItem"} onClick={()=>this.navigateToPage(item.btn)}><a key={item.btn} className={"sidebarButton"}>{item.name} ({item.btn})</a></div>
                );

                var buttonMap = this.generateButtonMap();
                return(

                    <div className={"page"}>
                        <EventListener onKeyDown={this.handleKeyPress} target="window"/>
                        <div className={"sidebar"}>

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
