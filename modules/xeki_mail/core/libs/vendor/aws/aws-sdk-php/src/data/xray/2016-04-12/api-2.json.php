<?php
// This file was auto-generated from sdk-root/src/data/xray/2016-04-12/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2016-04-12', 'endpointPrefix' => 'xray', 'protocol' => 'rest-json', 'serviceFullName' => 'AWS X-Ray', 'signatureVersion' => 'v4', 'uid' => 'xray-2016-04-12', ], 'operations' => [ 'BatchGetTraces' => [ 'name' => 'BatchGetTraces', 'http' => [ 'method' => 'POST', 'requestUri' => '/Traces', ], 'input' => [ 'shape' => 'BatchGetTracesRequest', ], 'output' => [ 'shape' => 'BatchGetTracesResult', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'ThrottledException', ], ], ], 'GetServiceGraph' => [ 'name' => 'GetServiceGraph', 'http' => [ 'method' => 'POST', 'requestUri' => '/ServiceGraph', ], 'input' => [ 'shape' => 'GetServiceGraphRequest', ], 'output' => [ 'shape' => 'GetServiceGraphResult', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'ThrottledException', ], ], ], 'GetTraceGraph' => [ 'name' => 'GetTraceGraph', 'http' => [ 'method' => 'POST', 'requestUri' => '/TraceGraph', ], 'input' => [ 'shape' => 'GetTraceGraphRequest', ], 'output' => [ 'shape' => 'GetTraceGraphResult', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'ThrottledException', ], ], ], 'GetTraceSummaries' => [ 'name' => 'GetTraceSummaries', 'http' => [ 'method' => 'POST', 'requestUri' => '/TraceSummaries', ], 'input' => [ 'shape' => 'GetTraceSummariesRequest', ], 'output' => [ 'shape' => 'GetTraceSummariesResult', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'ThrottledException', ], ], ], 'PutTelemetryRecords' => [ 'name' => 'PutTelemetryRecords', 'http' => [ 'method' => 'POST', 'requestUri' => '/TelemetryRecords', ], 'input' => [ 'shape' => 'PutTelemetryRecordsRequest', ], 'output' => [ 'shape' => 'PutTelemetryRecordsResult', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'ThrottledException', ], ], ], 'PutTraceSegments' => [ 'name' => 'PutTraceSegments', 'http' => [ 'method' => 'POST', 'requestUri' => '/TraceSegments', ], 'input' => [ 'shape' => 'PutTraceSegmentsRequest', ], 'output' => [ 'shape' => 'PutTraceSegmentsResult', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'ThrottledException', ], ], ], ], 'shapes' => [ 'Alias' => [ 'type' => 'structure', 'members' => [ 'Name' => [ 'shape' => 'String', ], 'Names' => [ 'shape' => 'AliasNames', ], 'Type' => [ 'shape' => 'String', ], ], ], 'AliasList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Alias', ], ], 'AliasNames' => [ 'type' => 'list', 'member' => [ 'shape' => 'String', ], ], 'AnnotationKey' => [ 'type' => 'string', ], 'AnnotationValue' => [ 'type' => 'structure', 'members' => [ 'NumberValue' => [ 'shape' => 'NullableDouble', ], 'BooleanValue' => [ 'shape' => 'NullableBoolean', ], 'StringValue' => [ 'shape' => 'String', ], ], ], 'Annotations' => [ 'type' => 'map', 'key' => [ 'shape' => 'AnnotationKey', ], 'value' => [ 'shape' => 'ValuesWithServiceIds', ], ], 'BackendConnectionErrors' => [ 'type' => 'structure', 'members' => [ 'TimeoutCount' => [ 'shape' => 'NullableInteger', ], 'ConnectionRefusedCount' => [ 'shape' => 'NullableInteger', ], 'HTTPCode4XXCount' => [ 'shape' => 'NullableInteger', ], 'HTTPCode5XXCount' => [ 'shape' => 'NullableInteger', ], 'UnknownHostCount' => [ 'shape' => 'NullableInteger', ], 'OtherCount' => [ 'shape' => 'NullableInteger', ], ], ], 'BatchGetTracesRequest' => [ 'type' => 'structure', 'required' => [ 'TraceIds', ], 'members' => [ 'TraceIds' => [ 'shape' => 'TraceIdList', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'BatchGetTracesResult' => [ 'type' => 'structure', 'members' => [ 'Traces' => [ 'shape' => 'TraceList', ], 'UnprocessedTraceIds' => [ 'shape' => 'UnprocessedTraceIdList', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'Double' => [ 'type' => 'double', ], 'Edge' => [ 'type' => 'structure', 'members' => [ 'ReferenceId' => [ 'shape' => 'NullableInteger', ], 'StartTime' => [ 'shape' => 'Timestamp', ], 'EndTime' => [ 'shape' => 'Timestamp', ], 'SummaryStatistics' => [ 'shape' => 'EdgeStatistics', ], 'ResponseTimeHistogram' => [ 'shape' => 'Histogram', ], 'Aliases' => [ 'shape' => 'AliasList', ], ], ], 'EdgeList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Edge', ], ], 'EdgeStatistics' => [ 'type' => 'structure', 'members' => [ 'OkCount' => [ 'shape' => 'NullableLong', ], 'ErrorStatistics' => [ 'shape' => 'ErrorStatistics', ], 'FaultStatistics' => [ 'shape' => 'FaultStatistics', ], 'TotalCount' => [ 'shape' => 'NullableLong', ], 'TotalResponseTime' => [ 'shape' => 'NullableDouble', ], ], ], 'ErrorStatistics' => [ 'type' => 'structure', 'members' => [ 'ThrottleCount' => [ 'shape' => 'NullableLong', ], 'OtherCount' => [ 'shape' => 'NullableLong', ], 'TotalCount' => [ 'shape' => 'NullableLong', ], ], ], 'FaultStatistics' => [ 'type' => 'structure', 'members' => [ 'OtherCount' => [ 'shape' => 'NullableLong', ], 'TotalCount' => [ 'shape' => 'NullableLong', ], ], ], 'FilterExpression' => [ 'type' => 'string', 'max' => 2000, 'min' => 0, ], 'GetServiceGraphRequest' => [ 'type' => 'structure', 'required' => [ 'StartTime', 'EndTime', ], 'members' => [ 'StartTime' => [ 'shape' => 'Timestamp', ], 'EndTime' => [ 'shape' => 'Timestamp', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'GetServiceGraphResult' => [ 'type' => 'structure', 'members' => [ 'StartTime' => [ 'shape' => 'Timestamp', ], 'EndTime' => [ 'shape' => 'Timestamp', ], 'Services' => [ 'shape' => 'ServiceList', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'GetTraceGraphRequest' => [ 'type' => 'structure', 'required' => [ 'TraceIds', ], 'members' => [ 'TraceIds' => [ 'shape' => 'TraceIdList', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'GetTraceGraphResult' => [ 'type' => 'structure', 'members' => [ 'Services' => [ 'shape' => 'ServiceList', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'GetTraceSummariesRequest' => [ 'type' => 'structure', 'required' => [ 'StartTime', 'EndTime', ], 'members' => [ 'StartTime' => [ 'shape' => 'Timestamp', ], 'EndTime' => [ 'shape' => 'Timestamp', ], 'Sampling' => [ 'shape' => 'NullableBoolean', ], 'FilterExpression' => [ 'shape' => 'FilterExpression', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'GetTraceSummariesResult' => [ 'type' => 'structure', 'members' => [ 'TraceSummaries' => [ 'shape' => 'TraceSummaryList', ], 'ApproximateTime' => [ 'shape' => 'Timestamp', ], 'TracesProcessedCount' => [ 'shape' => 'NullableLong', ], 'NextToken' => [ 'shape' => 'String', ], ], ], 'Histogram' => [ 'type' => 'list', 'member' => [ 'shape' => 'HistogramEntry', ], ], 'HistogramEntry' => [ 'type' => 'structure', 'members' => [ 'Value' => [ 'shape' => 'Double', ], 'Count' => [ 'shape' => 'Integer', ], ], ], 'Http' => [ 'type' => 'structure', 'members' => [ 'HttpURL' => [ 'shape' => 'String', ], 'HttpStatus' => [ 'shape' => 'NullableInteger', ], 'HttpMethod' => [ 'shape' => 'String', ], 'Userxeki' => [ 'shape' => 'String', ], 'ClientIp' => [ 'shape' => 'String', ], ], ], 'Integer' => [ 'type' => 'integer', ], 'InvalidRequestException' => [ 'type' => 'structure', 'members' => [], 'exception' => true, ], 'NullableBoolean' => [ 'type' => 'boolean', ], 'NullableDouble' => [ 'type' => 'double', ], 'NullableInteger' => [ 'type' => 'integer', ], 'NullableLong' => [ 'type' => 'long', ], 'PutTelemetryRecordsRequest' => [ 'type' => 'structure', 'required' => [ 'TelemetryRecords', ], 'members' => [ 'TelemetryRecords' => [ 'shape' => 'TelemetryRecordList', ], 'EC2InstanceId' => [ 'shape' => 'String', ], 'Hostname' => [ 'shape' => 'String', ], 'ResourceARN' => [ 'shape' => 'String', ], ], ], 'PutTelemetryRecordsResult' => [ 'type' => 'structure', 'members' => [], ], 'PutTraceSegmentsRequest' => [ 'type' => 'structure', 'required' => [ 'TraceSegmentDocuments', ], 'members' => [ 'TraceSegmentDocuments' => [ 'shape' => 'TraceSegmentDocumentList', ], ], ], 'PutTraceSegmentsResult' => [ 'type' => 'structure', 'members' => [ 'UnprocessedTraceSegments' => [ 'shape' => 'UnprocessedTraceSegmentList', ], ], ], 'Segment' => [ 'type' => 'structure', 'members' => [ 'Id' => [ 'shape' => 'SegmentId', ], 'Document' => [ 'shape' => 'SegmentDocument', ], ], ], 'SegmentDocument' => [ 'type' => 'string', 'min' => 1, ], 'SegmentId' => [ 'type' => 'string', 'max' => 16, 'min' => 16, ], 'SegmentList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Segment', ], ], 'Service' => [ 'type' => 'structure', 'members' => [ 'ReferenceId' => [ 'shape' => 'NullableInteger', ], 'Name' => [ 'shape' => 'String', ], 'Names' => [ 'shape' => 'ServiceNames', ], 'Root' => [ 'shape' => 'NullableBoolean', ], 'AccountId' => [ 'shape' => 'String', ], 'Type' => [ 'shape' => 'String', ], 'State' => [ 'shape' => 'String', ], 'StartTime' => [ 'shape' => 'Timestamp', ], 'EndTime' => [ 'shape' => 'Timestamp', ], 'Edges' => [ 'shape' => 'EdgeList', ], 'SummaryStatistics' => [ 'shape' => 'ServiceStatistics', ], 'DurationHistogram' => [ 'shape' => 'Histogram', ], ], ], 'ServiceId' => [ 'type' => 'structure', 'members' => [ 'Name' => [ 'shape' => 'String', ], 'Names' => [ 'shape' => 'ServiceNames', ], 'AccountId' => [ 'shape' => 'String', ], 'Type' => [ 'shape' => 'String', ], ], ], 'ServiceIds' => [ 'type' => 'list', 'member' => [ 'shape' => 'ServiceId', ], ], 'ServiceList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Service', ], ], 'ServiceNames' => [ 'type' => 'list', 'member' => [ 'shape' => 'String', ], ], 'ServiceStatistics' => [ 'type' => 'structure', 'members' => [ 'OkCount' => [ 'shape' => 'NullableLong', ], 'ErrorStatistics' => [ 'shape' => 'ErrorStatistics', ], 'FaultStatistics' => [ 'shape' => 'FaultStatistics', ], 'TotalCount' => [ 'shape' => 'NullableLong', ], 'TotalResponseTime' => [ 'shape' => 'NullableDouble', ], ], ], 'String' => [ 'type' => 'string', ], 'TelemetryRecord' => [ 'type' => 'structure', 'members' => [ 'Timestamp' => [ 'shape' => 'Timestamp', ], 'SegmentsReceivedCount' => [ 'shape' => 'NullableInteger', ], 'SegmentsSentCount' => [ 'shape' => 'NullableInteger', ], 'SegmentsSpilloverCount' => [ 'shape' => 'NullableInteger', ], 'SegmentsRejectedCount' => [ 'shape' => 'NullableInteger', ], 'BackendConnectionErrors' => [ 'shape' => 'BackendConnectionErrors', ], ], ], 'TelemetryRecordList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TelemetryRecord', ], ], 'ThrottledException' => [ 'type' => 'structure', 'members' => [], 'error' => [ 'httpStatusCode' => 429, ], 'exception' => true, ], 'Timestamp' => [ 'type' => 'timestamp', ], 'Trace' => [ 'type' => 'structure', 'members' => [ 'Id' => [ 'shape' => 'TraceId', ], 'Duration' => [ 'shape' => 'NullableDouble', ], 'Segments' => [ 'shape' => 'SegmentList', ], ], ], 'TraceId' => [ 'type' => 'string', 'max' => 35, 'min' => 35, ], 'TraceIdList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TraceId', ], ], 'TraceList' => [ 'type' => 'list', 'member' => [ 'shape' => 'Trace', ], ], 'TraceSegmentDocument' => [ 'type' => 'string', ], 'TraceSegmentDocumentList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TraceSegmentDocument', ], ], 'TraceSummary' => [ 'type' => 'structure', 'members' => [ 'Id' => [ 'shape' => 'TraceId', ], 'Duration' => [ 'shape' => 'NullableDouble', ], 'ResponseTime' => [ 'shape' => 'NullableDouble', ], 'HasFault' => [ 'shape' => 'NullableBoolean', ], 'HasError' => [ 'shape' => 'NullableBoolean', ], 'HasThrottle' => [ 'shape' => 'NullableBoolean', ], 'IsPartial' => [ 'shape' => 'NullableBoolean', ], 'Http' => [ 'shape' => 'Http', ], 'Annotations' => [ 'shape' => 'Annotations', ], 'Users' => [ 'shape' => 'TraceUsers', ], 'ServiceIds' => [ 'shape' => 'ServiceIds', ], ], ], 'TraceSummaryList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TraceSummary', ], ], 'TraceUser' => [ 'type' => 'structure', 'members' => [ 'UserName' => [ 'shape' => 'String', ], 'ServiceIds' => [ 'shape' => 'ServiceIds', ], ], ], 'TraceUsers' => [ 'type' => 'list', 'member' => [ 'shape' => 'TraceUser', ], ], 'UnprocessedTraceIdList' => [ 'type' => 'list', 'member' => [ 'shape' => 'TraceId', ], ], 'UnprocessedTraceSegment' => [ 'type' => 'structure', 'members' => [ 'Id' => [ 'shape' => 'String', ], 'ErrorCode' => [ 'shape' => 'String', ], 'Message' => [ 'shape' => 'String', ], ], ], 'UnprocessedTraceSegmentList' => [ 'type' => 'list', 'member' => [ 'shape' => 'UnprocessedTraceSegment', ], ], 'ValueWithServiceIds' => [ 'type' => 'structure', 'members' => [ 'AnnotationValue' => [ 'shape' => 'AnnotationValue', ], 'ServiceIds' => [ 'shape' => 'ServiceIds', ], ], ], 'ValuesWithServiceIds' => [ 'type' => 'list', 'member' => [ 'shape' => 'ValueWithServiceIds', ], ], ],];
