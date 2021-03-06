import React, {Component} from 'react';
import {Link, BrowserRouter} from 'react-router-dom';


class ItemPost extends Component {
    constructor(props) {
        super(props);
        this.state =
            {hits: this.props.column};
    }

    render() {
        console.log("in item", this.state);
        if (this.props.hits !== '') {
            return (
                <div className="back">
                    {this.props.column.map((hits) => //todo попробовать вынести в функцию
                        <div className="panel-body post panel">
                            <h5>{hits.client || ''}</h5>
                            <h6>{hits.project_name || ''}</h6>
                            <nav>
                                <Link className="more_btn" to={"/projectsView/" + hits.id}> подробнее </Link>
                            </nav>
                        </div>
                    )}
                </div>
            )
        }
        return <p>пока проектов</p>

    }
}

export default ItemPost;
