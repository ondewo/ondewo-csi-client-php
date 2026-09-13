<?php
// GENERATED CODE -- DO NOT EDIT!

// Original file comments:
// Copyright 2021-2025 ONDEWO GmbH
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
namespace Ondewo\Csi;

/**
 * <p>Endpoints of CSI service.</p>
 */
class ConversationsClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * <p>Create the S2S pipeline specified in the request message. The pipeline with the specified ID must not exist.</p>
     *
     * <p>Examples:</p>
     *
     * <pre>
     * grpcurl -plaintext -d '{
     *   "id": "pizza",
     *   "s2t_pipeline_id": "default_german",
     *   "nlu_project_id": "1f3425d2-41fd-4970-87e6-88e8e121bb49",
     *   "nlu_language_code": "de",
     *   "t2s_pipeline_id": "default_german"
     * }' localhost:50051 ondewo.csi.Conversations.CreateS2sPipeline
     * </pre>
     * <samp>{}</samp>
     * @param \Ondewo\Csi\S2sPipeline $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function CreateS2sPipeline(\Ondewo\Csi\S2sPipeline $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/CreateS2sPipeline',
        $argument,
        ['\Google\Protobuf\GPBEmpty', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>Retrieve the S2S pipeline with the ID specified in the request message.</p>
     *
     * <p>Examples:</p>
     *
     * <pre>
     * grpcurl -plaintext -d '{"id": "pizza"}' localhost:50051 ondewo.csi.Conversations.GetS2sPipeline
     * </pre>
     * <samp>{
     *   "id": "pizza",
     *   "s2t_pipeline_id": "default_german",
     *   "nlu_project_id": "1f3425d2-41fd-4970-87e6-88e8e121bb49",
     *   "nlu_language_code": "de",
     *   "t2s_pipeline_id": "default_german"
     * }
     * </samp>
     * @param \Ondewo\Csi\S2sPipelineId $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetS2sPipeline(\Ondewo\Csi\S2sPipelineId $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/GetS2sPipeline',
        $argument,
        ['\Ondewo\Csi\S2sPipeline', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>Update the S2S pipeline specified in the request message. The pipeline must exist.</p>
     *
     * <p>Examples:</p>
     *
     * <pre>
     * grpcurl -plaintext -d '{
     *   "id": "pizza",
     *   "s2t_pipeline_id": "default_german",
     *   "nlu_project_id": "1f3425d2-41fd-4970-87e6-88e8e121bb49",
     *   "nlu_language_code": "en",
     *   "t2s_pipeline_id": "default_german"
     * }' localhost:50051 ondewo.csi.Conversations.UpdateS2sPipeline
     * </pre>
     * <samp>{}</samp>
     * @param \Ondewo\Csi\S2sPipeline $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function UpdateS2sPipeline(\Ondewo\Csi\S2sPipeline $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/UpdateS2sPipeline',
        $argument,
        ['\Google\Protobuf\GPBEmpty', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>Delete the S2S pipeline with the ID specified in the request message. The pipeline must exist.</p>
     *
     * <p>Examples:</p>
     *
     * <pre>
     * grpcurl -plaintext -d '{"id": "pizza"}' localhost:50051 ondewo.csi.Conversations.DeleteS2sPipeline
     * </pre>
     * <samp>{}</samp>
     * @param \Ondewo\Csi\S2sPipelineId $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DeleteS2sPipeline(\Ondewo\Csi\S2sPipelineId $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/DeleteS2sPipeline',
        $argument,
        ['\Google\Protobuf\GPBEmpty', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>List all S2S pipelines of the server.</p>
     *
     * <p>Examples:</p>
     *
     * <pre>
     * grpcurl -plaintext localhost:50051 ondewo.csi.Conversations.ListS2sPipelines
     * </pre>
     * <samp>{
     *   "pipelines": [
     *     {
     *       "id": "pizza",
     *       "s2t_pipeline_id": "default_german",
     *       "nlu_project_id": "1f3425d2-41fd-4970-87e6-88e8e121bb49",
     *       "nlu_language_code": "de",
     *       "t2s_pipeline_id": "default_german"
     *     }
     *   ]
     * }</samp>
     * @param \Ondewo\Csi\ListS2sPipelinesRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ListS2sPipelines(\Ondewo\Csi\ListS2sPipelinesRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/ListS2sPipelines',
        $argument,
        ['\Ondewo\Csi\ListS2sPipelinesResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>Processes a natural language query in audio format in a streaming fashion and returns structured, actionable data as a result.</p>
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\BidiStreamingCall
     */
    public function S2sStream($metadata = [], $options = []) {
        return $this->_bidiRequest('/ondewo.csi.Conversations/S2sStream',
        ['\Ondewo\Csi\S2sStreamResponse','decode'],
        $metadata, $options);
    }

    /**
     * <p>Check the health of S2T, NLU and T2S servers.</p>
     *
     * <p>Examples:</p>
     *
     * <pre>
     * grpcurl -plaintext localhost:50051 ondewo.csi.Conversations.CheckUpstreamHealth
     * </pre>
     *
     * All upstreams healthy:
     * <samp>{}</samp>
     *
     * All upstreams unhealthy:
     * <samp>{
     *   "s2t_status": {
     *     "code": 14,
     *     "message": "failed to connect to all addresses"
     *   },
     *   "nlu_status": {
     *     "code": 14,
     *     "message": "failed to connect to all addresses"
     *   },
     *   "t2s_status": {
     *     "code": 14,
     *     "message": "failed to connect to all addresses"
     *   }
     * }</samp>
     * @param \Google\Protobuf\GPBEmpty $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function CheckUpstreamHealth(\Google\Protobuf\GPBEmpty $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/CheckUpstreamHealth',
        $argument,
        ['\Ondewo\Csi\CheckUpstreamHealthResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>Get the control stream to control sip, t2s, s2t etc. during a conversation.</p>
     * @param \Ondewo\Csi\ControlStreamRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\ServerStreamingCall
     */
    public function GetControlStream(\Ondewo\Csi\ControlStreamRequest $argument,
      $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/ondewo.csi.Conversations/GetControlStream',
        $argument,
        ['\Ondewo\Csi\ControlStreamResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * <p>Send a message on the control stream to control sip, t2s, s2t etc. during a conversation.</p>
     * @param \Ondewo\Csi\SetControlStatusRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function SetControlStatus(\Ondewo\Csi\SetControlStatusRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/ondewo.csi.Conversations/SetControlStatus',
        $argument,
        ['\Ondewo\Csi\SetControlStatusResponse', 'decode'],
        $metadata, $options);
    }

}
