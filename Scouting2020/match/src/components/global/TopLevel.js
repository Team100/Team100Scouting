import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import 'antd/dist/antd.css';
import DataLayoutPage from "./DataLayoutPage";
import RunnerPage from "./RunnerPage";
import CommentPage from "./CommentPage";
import UploadPage from "./UploadPage";
import Auth from "./Auth"; // or 'antd/dist/antd.less'

const initialState = {
    mode: "dataEntry",
    matchData: {},
    postMatch: {}
};


export default class TopLevel extends Component {
    componentWillMount() {
        this.configCallback = this.configCallback.bind(this);
        this.runnerCallback = this.runnerCallback.bind(this);
        this.postMatchCallback = this.postMatchCallback.bind(this);

        this.setState({mode: "dataEntry", pos: "B1"});
    }

    configCallback(params) {
        this.setState({mode: "match", config: params});
    }

    runnerCallback(params) {
        this.setState({mode: "postMatch", matchData: params});
    }

    postMatchCallback(params) {
        this.setState({mode: "upload", postMatch: params})
    }

    updatePositionCallback(params) {
        console.log("Updated Position: " + params);
        this.setState({pos: params});
    }

    resetPage() {
        console.log("resetting");
        this.setState(initialState);
        console.info(this.state);

    }

    render() {
        if (this.state.mode == "auth") {
            return <Auth/>
        }
        if (this.state.mode == "dataEntry") {
            return <DataLayoutPage pos={this.state.pos} callback={(params) => this.configCallback(params)}
                                   positionCallback={(params) => this.updatePositionCallback(params)}/>
        } else if (this.state.mode == "match") {
            return <RunnerPage callback={(params) => this.runnerCallback(params)}/>
        } else if (this.state.mode == "postMatch") {
            return (<CommentPage callback={(params) => this.postMatchCallback(params)}/>)
        } else if (this.state.mode == "upload") {
            return <UploadPage data={this.state} callback={() => this.resetPage()}/>;
        } else {
            this.setState({mode: "dataEntry"});
            return (<p>Loading</p>);
        }
    }
}
